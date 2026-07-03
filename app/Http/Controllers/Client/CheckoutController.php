<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\InvitationAddon;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class CheckoutController extends Controller
{
    public function index()
    {
        $invitations = Invitation::with([
            'template',
            'details',
            'orders' => function ($query) {
                $query->where('status', 'pending');
            },
        ])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('customer.invitations.index', compact('invitations'));
    }

    public function process(Request $request, $invitation_id)
    {
        $invitation = Invitation::where('user_id', Auth::id())->findOrFail($invitation_id);
        $type = $request->query('type');

        if (str_contains($type, 'addon') && $invitation->status !== 'active') {
            return back()->with('error', 'Maaf, Anda harus mengaktifkan Paket Undangan terlebih dahulu sebelum bisa melakukan Top Up kuota blast.');
        }

        return back()->with('error', 'Silakan gunakan tombol di halaman dashboard untuk memunculkan pop-up pembayaran.');
    }

    public function getSnapToken(Request $request)
    {
        try {
            $invitation_id = $request->input('invitation_id');
            $type = $request->input('type');

            $invitation = Invitation::where('user_id', Auth::id())->findOrFail($invitation_id);
            $adminFee = 1500;

            $order = null;
            $productDetails = '';
            $basePrice = 0;

            // ====================================================
            // 🔥 LOGIKA 1: AKTIVASI / UPGRADE PAKET
            // ====================================================
            if (str_starts_with($type, 'package_')) {
                $packageName = strtoupper(str_replace('package_', '', $type));
                $package = Package::where('name', $packageName)->first();

                // Fallback: Check if there's any pending package order for this invitation
                if (!$package) {
                    $pendingOrder = Order::with('package')
                        ->where('user_id', Auth::id())
                        ->where('invitation_id', $invitation->id)
                        ->whereNotNull('package_id')
                        ->where('status', 'pending')
                        ->latest()
                        ->first();
                    if ($pendingOrder && $pendingOrder->package) {
                        $package = $pendingOrder->package;
                    }
                }

                if (!$package) {
                    return response()->json(['error' => 'Data pesanan tidak ditemukan. Mungkin sudah lunas.'], 404);
                }

                // Cari paket berbayar terakhir yang lunas/success untuk menghitung selisih (jika upgrade)
                $currentPaidOrder = Order::with('package')
                    ->where('invitation_id', $invitation->id)
                    ->whereNotNull('package_id')
                    ->where('status', 'success')
                    ->latest()
                    ->first();

                $currentPackagePrice = 0;
                if ($currentPaidOrder && $currentPaidOrder->package) {
                    $currentPackagePrice = (int) $currentPaidOrder->package->price;
                }

                $basePrice = (int) $package->price - $currentPackagePrice;

                if ($basePrice <= 0) {
                    return response()->json(['error' => 'Anda sudah memiliki paket ini atau paket yang lebih tinggi.'], 400);
                }

                $productDetails = 'Aktivasi Paket: ' . $package->name;

                // Cari order pending untuk paket target ini, jika ada gunakan. Jika tidak, buat baru.
                $order = Order::where('user_id', Auth::id())
                    ->where('invitation_id', $invitation->id)
                    ->where('package_id', $package->id)
                    ->where('status', 'pending')
                    ->latest()
                    ->first();

                if (!$order) {
                    $order = Order::create([
                        'order_number' => 'INV-' . time() . strtoupper(Str::random(5)),
                        'user_id' => Auth::id(),
                        'invitation_id' => $invitation->id,
                        'package_id' => $package->id,
                        'amount' => $basePrice,
                        'status' => 'pending',
                    ]);
                } else {
                    if ($order->amount != $basePrice) {
                        $order->update([
                            'amount' => $basePrice,
                            'payment_url' => null, // Reset token
                        ]);
                    }
                }
            }
            // ====================================================
            // 🔥 LOGIKA 2: TOP UP KUOTA (Addon)
            // ====================================================
            else {
                if ($invitation->status !== 'active') {
                    return response()->json(['error' => 'Maaf, Anda harus mengaktifkan Paket Undangan terlebih dahulu.'], 400);
                }

                $basePrice = $type === 'addon_blast_100' ? 25000 : 100000;
                $productDetails = $type === 'addon_blast_100' ? 'Top Up 100 Kuota WA Blast' : 'Top Up 500 Kuota WA Blast';

                $order = Order::where('user_id', Auth::id())->where('invitation_id', $invitation->id)->whereNull('package_id')->where('status', 'pending')->latest()->first();

                if (!$order) {
                    $order = Order::create([
                        'order_number' => 'INV-' . time() . strtoupper(Str::random(5)),
                        'user_id' => Auth::id(),
                        'invitation_id' => $invitation->id,
                        'package_id' => null,
                        'amount' => $basePrice,
                        'status' => 'pending',
                    ]);
                }
            }

            // ====================================================
            // 🔥 EKSEKUSI KE MIDTRANS
            // ====================================================

            // ====================================================
            // 🔥 EKSEKUSI KE PAYMENT GATEWAY DINAMIS
            // ====================================================
            $activeGateway = \App\Models\Setting::get('active_payment_gateway', 'midtrans');

            if (!empty($order->payment_url)) {
                return response()->json([
                    'snap_token' => $order->payment_url,
                    'redirect_url' => $order->payment_url,
                    'active_gateway' => $activeGateway,
                ]);
            }

            // HARGA TOTAL = (Harga Paket Asli) + (Biaya Admin)
            $totalAmount = $basePrice + $adminFee;

            $gateway = \App\Services\Payment\PaymentGatewayManager::active();
            $result = $gateway->createTransaction($order, $type, $productDetails, $basePrice, $adminFee);

            if (isset($result['error'])) {
                return response()->json(['error' => $result['error']], 500);
            }

            $tokenOrUrl = $result['token'] ?? '';
            $redirectUrl = $result['redirect_url'] ?? '';

            // Jika Midtrans, simpan tokennya. Jika Duitku/lainnya, simpan redirect_url.
            $saveValue = $activeGateway === 'midtrans' ? $tokenOrUrl : $redirectUrl;
            $order->update(['payment_url' => $saveValue]);

            return response()->json([
                'snap_token' => $tokenOrUrl,
                'redirect_url' => $redirectUrl,
                'active_gateway' => $activeGateway,
            ]);
        } catch (\Throwable $th) {
            Log::error('Error getSnapToken: ' . $th->getMessage());
            return response()->json(['error' => 'Terjadi kendala server: ' . $th->getMessage()], 500);
        }
    }

    private function getActiveDays($packageId)
    {
        $package = Package::find($packageId);
        if (!$package) {
            return 30;
        }

        // Ambil data features
        $features = $package->features;

        // Jika features masih berupa string (karena double encoding di DB), decode lagi
        if (is_string($features)) {
            $features = json_decode($features, true);
        }

        // Jika setelah di-decode pertama hasilnya masih string JSON, decode sekali lagi
        if (is_string($features)) {
            $features = json_decode($features, true);
        }

        // Ambil nilai dari path logic -> active_days
        return $features['logic']['active_days'] ?? 30;
    }

    // 🔥 FUNGSI CALLBACK WEBHOOK
    public function callback(Request $request)
    {
        Log::info('=== WEBHOOK CALLBACK MASUK ===');
        try {
            $gateway = \App\Services\Payment\PaymentGatewayManager::active();
            $payload = $gateway->handleNotification($request);

            if (!$payload['success']) {
                Log::warning('CALLBACK VERIFICATION GAGAL: ' . ($payload['message'] ?? 'Unknown Error'));
                return response()->json(['message' => $payload['message'] ?? 'Invalid Callback'], 403);
            }

            $order = Order::where('order_number', $payload['order_number'])->first();

            if (!$order) {
                Log::warning('GAGAL: Order ID ' . $payload['order_number'] . ' tidak ditemukan di DB.');
                return response()->json(['message' => 'Order Not Found'], 404);
            }

            if ($order->status == 'pending') {
                if ($payload['status'] === 'success') {
                    $order->update([
                        'status' => 'success',
                        'reference' => $payload['transaction_id'],
                    ]);

                    Payment::create([
                        'order_id' => $order->id,
                        'transaction_id' => $payload['transaction_id'],
                        'payment_type' => $payload['payment_type'],
                        'payload' => json_encode($payload['raw_payload']),
                    ]);

                    if ($order->package_id != null) {
                        $invitation = Invitation::find($order->invitation_id);
                        if ($invitation) {
                            $activeDays = $this->getActiveDays($order->package_id);
                            $invitation->update([
                                'status' => 'active',
                                'expires_at' => now()->addDays($activeDays),
                            ]);
                        }
                    } else {
                        // Karena order->amount sekarang murni 25.000 atau 100.000, tidak perlu dikurangi lagi
                        $baseTagihan = (int) $order->amount;
                        $tambahBerapa = $baseTagihan == 25000 ? 100 : 500;
                        $addon = InvitationAddon::firstOrCreate(['invitation_id' => $order->invitation_id]);
                        $addon->increment('extra_quota', $tambahBerapa);
                    }
                } elseif (in_array($payload['status'], ['expired', 'failed'])) {
                    $order->update([
                        'status' => $payload['status'],
                    ]);
                }
            }
            return response()->json(['message' => 'Success']);
        } catch (\Throwable $th) {
            Log::error('Callback handling error: ' . $th->getMessage());
            return response()->json(['message' => 'Internal Error: ' . $th->getMessage()], 500);
        }
    }

    // 🔥 FRONTEND CALLBACK
    public function frontendCallback(Request $request)
    {
        $result = $request->all();
        $orderId = $result['order_id'] ?? null;
        $paymentType = $result['payment_type'] ?? 'unknown';

        if (!$orderId) {
            return response()->json(['success' => false, 'message' => 'Order ID tidak ditemukan']);
        }

        $gatewayName = \App\Models\Setting::get('active_payment_gateway', 'midtrans');
        if ($gatewayName !== 'midtrans') {
            $order = Order::where('order_number', $orderId)->first();
            if ($order && $order->status === 'success') {
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false, 'message' => 'Pembayaran belum terkonfirmasi oleh server.']);
        }

        Config::$serverKey = Setting::get('midtrans_server_key', config('midtrans.server_key'));
        Config::$isProduction = filter_var(Setting::get('midtrans_is_production', config('midtrans.is_production')), FILTER_VALIDATE_BOOLEAN);

        try {
            $midtransStatus = Transaction::status($orderId);

            if ($midtransStatus && in_array($midtransStatus->transaction_status, ['capture', 'settlement'])) {
                $order = Order::where('order_number', $orderId)->first();

                if ($order && $order->status === 'pending') {
                    $order->update([
                        'status' => 'success',
                        'reference' => $midtransStatus->transaction_id ?? ($result['transaction_id'] ?? null),
                    ]);

                    Payment::create([
                        'order_id' => $order->id,
                        'transaction_id' => $midtransStatus->transaction_id ?? ($result['transaction_id'] ?? null),
                        'payment_type' => $paymentType,
                        'payload' => json_encode($midtransStatus),
                    ]);

                    if ($order->package_id != null) {
                        $invitation = Invitation::find($order->invitation_id);
                        if ($invitation) {
                            $activeDays = $this->getActiveDays($order->package_id);
                            $invitation->update([
                                'status' => 'active',
                                'expires_at' => now()->addDays($activeDays),
                            ]);
                        }
                    } else {
                        $baseTagihan = $order->amount - 1500;
                        $tambahBerapa = $baseTagihan == 25000 ? 100 : 500;
                        $addon = InvitationAddon::firstOrCreate(['invitation_id' => $order->invitation_id]);
                        $addon->increment('extra_quota', $tambahBerapa);
                    }
                }
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Status di Midtrans belum lunas.']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Transaksi tidak valid.']);
        }
    }
}

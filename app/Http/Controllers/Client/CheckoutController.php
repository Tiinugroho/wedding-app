<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\InvitationAddon;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
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
        $invitation_id = $request->input('invitation_id');
        $type = $request->input('type');

        $invitation = Invitation::where('user_id', Auth::id())->findOrFail($invitation_id);

        if (str_contains($type, 'addon') && $invitation->status !== 'active') {
            return response()->json(['error' => 'Maaf, Anda harus mengaktifkan Paket Undangan terlebih dahulu.'], 400);
        }

        $adminFee = 1500;
        $order = null;
        $baseAmount = 0;
        $productDetails = "";
        $totalAmount = 0;

        // ====================================================
        // 🔥 LOGIKA 1: AKTIVASI PAKET PREMIUM
        // ====================================================
        if ($type === 'package_premium') {
            
            // Cari order pending dari database
            $order = Order::with('package')->where('user_id', Auth::id())
                ->where('invitation_id', $invitation->id)
                ->where('status', 'pending')
                ->whereNotNull('package_id')
                ->latest()
                ->first();

            if (!$order) {
                return response()->json(['error' => 'Data pesanan tidak ditemukan. Mungkin sudah kedaluwarsa atau lunas.'], 404);
            }

            // Ambil harga dari order (yang sudah termasuk fee di versi terakhir)
            // Jika kamu sudah memperbaiki InvitationController agar amount order = harga paket + admin fee,
            // maka kita gunakan order->amount langsung.
            $totalAmount = $order->amount; 
            $baseAmount = $totalAmount - $adminFee;
            $productDetails = "Aktivasi Paket Premium";

            // Pastikan jika ada ketidaksesuaian kita perbaiki (seharusnya tidak terjadi lagi jika InvitationController sudah benar)
            if ($totalAmount != ($order->package->price + $adminFee)) {
                 $totalAmount = $order->package->price + $adminFee;
                 $baseAmount = $order->package->price;
                 $order->update(['amount' => $totalAmount]);
            }


        // ====================================================
        // 🔥 LOGIKA 2: TOP UP KUOTA (ADDON)
        // ====================================================
        } else {
            if ($type === 'addon_blast_100') {
                $baseAmount = 25000;
                $productDetails = "Top Up 100 Kuota WA Blast";
            } elseif ($type === 'addon_blast_500') {
                $baseAmount = 100000;
                $productDetails = "Top Up 500 Kuota WA Blast";
            } else {
                return response()->json(['error' => 'Pilihan layanan tidak valid.'], 400);
            }

            $totalAmount = $baseAmount + $adminFee;

            $order = Order::where('user_id', Auth::id())
                ->where('invitation_id', $invitation->id)
                ->where('status', 'pending')
                ->whereNull('package_id')
                ->where('amount', $totalAmount)
                ->first();

            if (!$order) {
                $order = Order::create([
                    'order_number'  => 'INV-' . strtoupper(Str::random(8)),
                    'user_id'       => Auth::id(),
                    'invitation_id' => $invitation->id,
                    'package_id'    => null,
                    'amount'        => $totalAmount,
                    'status'        => 'pending',
                ]);
            }
        }

        // 3. Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id'     => $order->order_number, 
                'gross_amount' => (int) $totalAmount, 
            ],
            'item_details' => [
                [
                    'id'       => $type,
                    'price'    => (int) $baseAmount, 
                    'quantity' => 1,
                    'name'     => $productDetails
                ],
                [
                    'id'       => 'admin_fee',
                    'price'    => (int) $adminFee, 
                    'quantity' => 1,
                    'name'     => 'Biaya Layanan Aplikasi'
                ]
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email'      => Auth::user()->email,
                'phone'      => Auth::user()->phone_number ?? '081234567890',
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghubungi server Midtrans: ' . $e->getMessage()], 500);
        }
    }

    // 🔥 FUNGSI CALLBACK DENGAN SISTEM LOG PENDETEKSI ERROR 🔥
    public function callback(Request $request)
    {
        Log::info('=== MIDTRANS CALLBACK MASUK ===');
        Log::info($request->all());

        $serverKey = config('midtrans.server_key');
        // PENTING: Gunakan gross_amount persis seperti yang dikirim Midtrans (termasuk desimal .00)
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            Log::info('KUNCI VALID. Status Transaksi: ' . $request->transaction_status);

            $order = Order::where('order_number', $request->order_id)->first();

            if (!$order) {
                Log::warning('GAGAL: Order ID ' . $request->order_id . ' tidak ditemukan di DB.');
                return response()->json(['message' => 'Order Not Found'], 404);
            }

            // Hanya proses jika status order masih pending
            if ($order->status == 'pending') {
                
                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    Log::info('Order ditemukan dan berstatus pending. Pembayaran Berhasil. Mulai proses Update DB...');
                    
                    $order->update([
                        'status' => 'success',
                        'reference' => $request->transaction_id
                    ]);
                    
                    Payment::create([
                        'order_id'       => $order->id,
                        'transaction_id' => $request->transaction_id,
                        'payment_type'   => $request->payment_type,
                        'payload'        => json_encode($request->all())
                    ]);

                    if ($order->package_id != null) {
                        $invitation = Invitation::find($order->invitation_id);
                        if ($invitation) {
                            $invitation->update([
                                'status'     => 'active',
                                'expires_at' => now()->addYear() 
                            ]);
                            Log::info('BERHASIL: Status undangan ' . $invitation->id . ' diubah menjadi Active!');
                        } else {
                            Log::error('GAGAL AKTIVASI: Undangan ' . $order->invitation_id . ' tidak ditemukan.');
                        }
                    } else {
                        // Logika untuk Addon Top Up
                        $baseTagihan = $order->amount - 1500; 
                        $tambahBerapa = ($baseTagihan == 25000) ? 100 : 500;

                        $addon = InvitationAddon::firstOrCreate(
                            ['invitation_id' => $order->invitation_id]
                        );
                        
                        $addon->increment('extra_quota', $tambahBerapa);
                        Log::info('BERHASIL: Kuota Addon ditambahkan sebesar ' . $tambahBerapa . ' untuk undangan ' . $order->invitation_id);
                    }

                } elseif ($request->transaction_status == 'expire' || $request->transaction_status == 'cancel' || $request->transaction_status == 'deny') {
                    Log::info('Pembayaran Gagal/Expired. Mengubah status order menjadi: ' . $request->transaction_status);
                    
                    $status = ($request->transaction_status == 'expire') ? 'expired' : 'failed';
                    
                    $order->update([
                        'status' => $status
                    ]);
                }
            } else {
                Log::info('Order ' . $request->order_id . ' sudah diproses sebelumnya (Status: ' . $order->status . '). Mengabaikan notifikasi ini.');
            }

            return response()->json(['message' => 'Success']);
        }
        
        Log::error('KUNCI GAGAL: Hashed berbeda dengan Signature Key dari Midtrans.');
        return response()->json(['message' => 'Invalid Signature'], 403);
    }

    // 🔥 FUNGSI BARU: MENERIMA SUKSES LANGSUNG DARI BROWSER (LOCALHOST FRIENDLY) 🔥
    public function frontendCallback(Request $request)
    {
        // 1. Tangkap hasil dari Javascript Midtrans
        $result = $request->all();
        
        $orderId = $result['order_id'] ?? null;
        $transactionStatus = $result['transaction_status'] ?? null;
        $paymentType = $result['payment_type'] ?? 'unknown'; // Ini menangkap metode pembayaran (qris, bank_transfer, dll)
        
        // 2. Pastikan transaksi benar-benar sukses di layar
        if (!$orderId || !in_array($transactionStatus, ['capture', 'settlement'])) {
            return response()->json(['success' => false, 'message' => 'Transaksi belum selesai']);
        }

        // 3. Cari Order berdasarkan ID yang dibayar
        $order = Order::where('order_number', $orderId)->first();
        
        if ($order && $order->status === 'pending') {
            
            // A. Update status Order jadi success
            $order->update([
                'status' => 'success',
                'reference' => $result['transaction_id'] ?? null
            ]);
            
            // B. 🔥 SIMPAN JENIS METODE PEMBAYARAN KE TABEL PAYMENTS 🔥
            Payment::create([
                'order_id'       => $order->id,
                'transaction_id' => $result['transaction_id'] ?? null,
                'payment_type'   => $paymentType,
                'payload'        => json_encode($result)
            ]);

            // C. Proses Aktivasi (Sama persis seperti webhook)
            if ($order->package_id != null) {
                $invitation = Invitation::find($order->invitation_id);
                if ($invitation) {
                    $invitation->update([
                        'status'     => 'active',
                        'expires_at' => now()->addYear() 
                    ]);
                }
            } else {
                $baseTagihan = $order->amount - 1500; 
                $tambahBerapa = ($baseTagihan == 25000) ? 100 : 500;
                $addon = InvitationAddon::firstOrCreate(['invitation_id' => $order->invitation_id]);
                $addon->increment('extra_quota', $tambahBerapa);
            }
        }

        return response()->json(['success' => true]);
    }
}
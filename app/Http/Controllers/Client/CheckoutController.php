<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function process($invitation_id)
    {
        $invitation = Invitation::where('user_id', Auth::id())->findOrFail($invitation_id);
        
        // Cek order terakhir yang pending
        $order = Order::where('invitation_id', $invitation->id)->where('status', 'pending')->first();

        // Jika tidak ada order pending, buat baru
        if (!$order) {
            // Asumsi: package_id disimpan di invitation atau kamu ambil dari form
            // Di sini kita contohkan mengambil dari order sebelumnya atau default
            $package = \App\Models\Package::find(1); // Ganti dengan logika paket yang dipilih klien
            
            $order = Order::create([
                'order_number' => 'INV-' . strtoupper(Str::random(8)),
                'user_id' => Auth::id(),
                'invitation_id' => $invitation->id,
                'amount' => $package->price,
                'package_id' => $package->id,
                'status' => 'pending',
            ]);
        }

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Jika belum punya snap_token, minta ke Midtrans
        if (!$order->snap_token) {
            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_number,
                    'gross_amount' => $order->amount,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ]
            ];

            $snapToken = Snap::getSnapToken($params);
            $order->update(['snap_token' => $snapToken]);
        }

        return response()->json(['snap_token' => $order->snap_token]);
    }

    // Ini adalah Webhook / Callback yang akan dipanggil oleh Midtrans secara diam-diam
    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                
                $order = Order::where('order_number', $request->order_id)->first();
                
                if ($order && $order->status == 'pending') {
                    // 1. Update Order
                    $order->update(['status' => 'success']);
                    
                    // 2. Catat Pembayaran
                    Payment::create([
                        'order_id' => $order->id,
                        'transaction_id' => $request->transaction_id,
                        'payment_type' => $request->payment_type,
                        'payload' => json_encode($request->all())
                    ]);

                    // 3. AKTIFKAN UNDANGAN!
                    $invitation = Invitation::find($order->invitation_id);
                    $invitation->update([
                        'status' => 'active',
                        // Set masa aktif, misal +1 tahun dari sekarang
                        'expires_at' => now()->addYear() 
                    ]);
                }
            }
        }
        return response()->json(['message' => 'Callback received']);
    }
}
<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransGateway implements PaymentGatewayInterface
{
    public function __construct()
    {
        // Load settings with fallback to original configs
        Config::$serverKey = Setting::get('midtrans_server_key', config('midtrans.server_key'));
        Config::$clientKey = Setting::get('midtrans_client_key', config('midtrans.client_key'));
        
        $isProd = Setting::get('midtrans_is_production', config('midtrans.is_production'));
        // Convert to boolean
        Config::$isProduction = filter_var($isProd, FILTER_VALIDATE_BOOLEAN);
        Config::$isSanitized = filter_var(Setting::get('midtrans_is_sanitized', config('midtrans.is_sanitized', true)), FILTER_VALIDATE_BOOLEAN);
        Config::$is3ds = filter_var(Setting::get('midtrans_is_3ds', config('midtrans.is_3ds', true)), FILTER_VALIDATE_BOOLEAN);
    }

    public function createTransaction(Order $order, string $type, string $productDetails, int $basePrice, int $adminFee): array
    {
        $totalAmount = $basePrice + $adminFee;
        
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => $totalAmount,
            ],
            'item_details' => [
                [
                    'id' => substr($type, 0, 50),
                    'price' => $basePrice,
                    'quantity' => 1,
                    'name' => substr($productDetails, 0, 50),
                ],
                [
                    'id' => 'admin_fee',
                    'price' => $adminFee,
                    'quantity' => 1,
                    'name' => 'Biaya Layanan Aplikasi',
                ],
            ],
            'customer_details' => [
                'first_name' => substr(Auth::user()->name, 0, 50),
                'email' => Auth::user()->email,
                'phone' => Auth::user()->phone_number ?? '081234567890',
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return [
                'token' => $snapToken,
                'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken
            ];
        } catch (\Throwable $th) {
            Log::error('Midtrans createTransaction Error: ' . $th->getMessage());
            return [
                'error' => $th->getMessage()
            ];
        }
    }

    public function handleNotification(Request $request): array
    {
        $serverKey = Setting::get('midtrans_server_key', config('midtrans.server_key'));
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            Log::error('Midtrans signature verification failed.');
            return ['success' => false, 'message' => 'Invalid Signature'];
        }

        $status = 'failed';
        if (in_array($request->transaction_status, ['capture', 'settlement'])) {
            $status = 'success';
        } elseif (in_array($request->transaction_status, ['pending'])) {
            $status = 'pending';
        } elseif ($request->transaction_status === 'expire') {
            $status = 'expired';
        }

        return [
            'success' => true,
            'status' => $status,
            'order_number' => $request->order_id,
            'transaction_id' => $request->transaction_id,
            'payment_type' => $request->payment_type ?? 'midtrans',
            'raw_payload' => $request->all(),
        ];
    }

    public function getClientScriptUrl(): string
    {
        $isProd = Setting::get('midtrans_is_production', config('midtrans.is_production'));
        $isProd = filter_var($isProd, FILTER_VALIDATE_BOOLEAN);

        return $isProd 
            ? 'https://app.midtrans.com/snap/snap.js' 
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }
}

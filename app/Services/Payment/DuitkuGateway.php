<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DuitkuGateway implements PaymentGatewayInterface
{
    protected $merchantCode;
    protected $apiKey;
    protected $isProduction;
    protected $apiUrl;

    public function __construct()
    {
        $this->merchantCode = Setting::get('duitku_merchant_code', config('duitku.merchant_code'));
        $this->apiKey = Setting::get('duitku_merchant_key', config('duitku.merchant_key'));
        
        $isProdSetting = Setting::get('duitku_is_production', config('duitku.env', 'sandbox') === 'production');
        $this->isProduction = filter_var($isProdSetting, FILTER_VALIDATE_BOOLEAN);

        $this->apiUrl = $this->isProduction 
            ? 'https://passport.duitku.com/webapi/api/merchant/v2/inquiry'
            : 'https://sandbox.duitku.com/webapi/api/merchant/v2/inquiry';
    }

    public function createTransaction(Order $order, string $type, string $productDetails, int $basePrice, int $adminFee): array
    {
        $totalAmount = $basePrice + $adminFee;
        
        // Generate signature
        // signature = MD5(merchantCode + merchantOrderId + paymentAmount + apiKey)
        $signature = md5($this->merchantCode . $order->order_number . $totalAmount . $this->apiKey);

        $callbackUrl = route('midtrans.callback'); // Reuse callback endpoint or separate route
        $returnUrl = route('customer.orders.show', $order->id);

        $payload = [
            'merchantCode' => $this->merchantCode,
            'paymentAmount' => $totalAmount,
            'merchantOrderId' => $order->order_number,
            'productDetails' => $productDetails,
            'email' => Auth::user()->email,
            'paymentMethod' => '', // Empty for payment list selection page
            'callbackUrl' => $callbackUrl,
            'returnUrl' => $returnUrl,
            'signature' => $signature,
            'expiryPeriod' => 120, // 2 hours
        ];

        try {
            $response = Http::post($this->apiUrl, $payload);
            
            if ($response->successful()) {
                $result = $response->json();
                
                if (isset($result['statusCode']) && $result['statusCode'] == '00') {
                    return [
                        'token' => $result['reference'] ?? $order->order_number,
                        'redirect_url' => $result['paymentUrl'] ?? '',
                    ];
                }
                
                return [
                    'error' => $result['statusMessage'] ?? 'Duitku Response Error (Unknown status code)'
                ];
            }

            return [
                'error' => 'Gagal terhubung ke API Duitku (HTTP Status ' . $response->status() . ')'
            ];
        } catch (\Throwable $th) {
            Log::error('Duitku createTransaction Error: ' . $th->getMessage());
            return [
                'error' => $th->getMessage()
            ];
        }
    }

    public function handleNotification(Request $request): array
    {
        // Duitku Callback fields:
        // merchantCode, amount, merchantOrderId, signature, reference, resultCode, paymentCode
        $merchantCode = $request->input('merchantCode');
        $amount = $request->input('amount');
        $merchantOrderId = $request->input('merchantOrderId');
        $signature = $request->input('signature');
        $reference = $request->input('reference');
        $resultCode = $request->input('resultCode');
        $paymentCode = $request->input('paymentCode');

        // Verify callback signature
        // signature = MD5(merchantCode + amount + merchantOrderId + apiKey)
        $expectedSignature = md5($this->merchantCode . $amount . $merchantOrderId . $this->apiKey);

        if ($signature !== $expectedSignature) {
            Log::error('Duitku Signature callback verification failed.');
            return ['success' => false, 'message' => 'Invalid Duitku Signature'];
        }

        $status = 'failed';
        if ($resultCode == '00') {
            $status = 'success';
        }

        return [
            'success' => true,
            'status' => $status,
            'order_number' => $merchantOrderId,
            'transaction_id' => $reference,
            'payment_type' => $paymentCode ?? 'duitku',
            'raw_payload' => $request->all(),
        ];
    }

    public function getClientScriptUrl(): string
    {
        return '';
    }
}

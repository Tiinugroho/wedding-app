<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Http\Request;

interface PaymentGatewayInterface
{
    /**
     * Create transaction / request token.
     * Returns an array containing keys like 'token' or 'redirect_url' or 'error'.
     */
    public function createTransaction(Order $order, string $type, string $productDetails, int $basePrice, int $adminFee): array;

    /**
     * Handle notification / webhook callback.
     * Returns array containing 'success' (bool), 'status' (string like success, pending, failed), 'order_number' (string), 'transaction_id' (string), and 'payment_type' (string).
     */
    public function handleNotification(Request $request): array;

    /**
     * Get JS library SDK URL.
     */
    public function getClientScriptUrl(): string;
}

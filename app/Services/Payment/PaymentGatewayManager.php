<?php

namespace App\Services\Payment;

use App\Models\Setting;
use Exception;

class PaymentGatewayManager
{
    /**
     * Get the active payment gateway driver.
     */
    public static function active(): PaymentGatewayInterface
    {
        $gatewayName = Setting::get('active_payment_gateway', 'midtrans');
        
        switch (strtolower($gatewayName)) {
            case 'midtrans':
                return new MidtransGateway();
            case 'duitku':
                return new DuitkuGateway();
            default:
                throw new Exception("Payment gateway driver [{$gatewayName}] tidak didukung.");
        }
    }
}

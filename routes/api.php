<?php

use App\Http\Controllers\Client\CheckoutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Webhook / Callback Payment Gateways (Tidak butuh CSRF Token)
Route::post('/callback/midtrans', [CheckoutController::class, 'callback']);
Route::post('/callback/duitku', [CheckoutController::class, 'callback']);
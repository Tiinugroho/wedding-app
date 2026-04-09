<?php

use App\Http\Controllers\Client\CheckoutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Route Webhook / Callback Duitku (Tidak butuh CSRF Token)
Route::post('/callback/duitku', [CheckoutController::class, 'callback']);
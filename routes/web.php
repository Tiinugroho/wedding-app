<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\MusicController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Client\AttendanceController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\DashboardController as CustomerDashboard;
use App\Http\Controllers\Client\InvitationController;
use App\Http\Controllers\Client\OrderController as CustomerOrderController;
use App\Http\Controllers\Client\WhatsappController; // 🔥 PASTIKAN INI DI-IMPORT
use App\Http\Controllers\FrontController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('welcome');
Route::get('/katalog-tema', [HomeController::class, 'katalog'])->name('katalog');

// ==========================================
// GOOGLE AUTH
// ==========================================
Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);
Route::get('/check-slug', [InvitationController::class, 'checkSlug'])->name('check.slug');

// 🔥 MIDTRANS CALLBACK (WEBHOOK) 🔥
// Wajib diletakkan di LUAR middleware auth, karena yang mengakses ini adalah server Midtrans secara otomatis.
Route::post('/midtrans/callback', [CheckoutController::class, 'callback'])->name('midtrans.callback');

// Grup besar: Harus Login & Email Verified
Route::middleware(['auth', 'verified'])->group(function () {
    // TRAFFIC CONTROLLER (PENGATUR ARAH OTOMATIS)
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('customer.dashboard');
    })->name('dashboard');

    // ==========================================
    // AREA ADMIN (Hanya bisa diakses role: 'admin')
    // ==========================================
    Route::middleware(['role:admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

            // Master Data
            Route::resource('packages', PackageController::class)->except(['show']);
            Route::resource('categories', CategoryController::class)->except(['show']);
            Route::resource('templates', TemplateController::class)->except(['show']);
            Route::resource('musics', MusicController::class)->except(['show']);

            // 🔥 Transaksi & User
            Route::resource('orders', AdminOrderController::class)->only(['index', 'show']);
            Route::resource('users', AdminUserController::class)->only(['index', 'destroy']);

            // Profile Admin
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
            Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');
        });

    // ==========================================
    // AREA CUSTOMER (Hanya bisa diakses role: 'client')
    // ==========================================
    Route::middleware(['role:client'])
        ->prefix('customer')
        ->name('customer.')
        ->group(function () {
            Route::get('/dashboard', [CustomerDashboard::class, 'index'])->name('dashboard');

            // Route Undangan Saya
            Route::resource('invitations', InvitationController::class);
            Route::post('invitations/{id}/gallery', [InvitationController::class, 'uploadGallery'])->name('invitations.gallery.upload');
            Route::delete('gallery/{id}', [InvitationController::class, 'deleteGallery'])->name('invitations.gallery.delete');

            // 🔥 ROUTE RIWAYAT PEMBAYARAN KLIEN 🔥
            Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
            Route::get('/orders/{id}', [CustomerOrderController::class, 'show'])->name('orders.show');

            // Profile Customer
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

            // 🔥 ROUTE CHECKOUT MIDTRANS 🔥
            Route::get('/checkout/{invitation_id}', [CheckoutController::class, 'process'])->name('checkout.process');
            Route::post('/checkout/get-snap-token', [CheckoutController::class, 'getSnapToken'])->name('checkout.token');
            Route::post('/checkout/success', [CheckoutController::class, 'frontendCallback'])->name('checkout.success');

            // ROUTE SCANNER BUKU TAMU
            Route::get('/invitations/{id}/scanner', [AttendanceController::class, 'scanner'])->name('invitations.scanner');

            // 🔥 ROUTE WA BLAST 🔥
            // Menambahkan kembali semua rute yang dibutuhkan oleh WhatsappController
            Route::get('/blast/{invitation_id}', [WhatsappController::class, 'index'])->name('blast.index');
            Route::post('/blast/import/{invitation_id}', [WhatsappController::class, 'importExcel'])->name('blast.import');
            Route::post('/blast/manual/{invitation_id}', [WhatsappController::class, 'storeGuest'])->name('blast.manual');
            Route::get('/blast/template-excel/download', [WhatsappController::class, 'downloadTemplate'])->name('blast.template');
            Route::delete('/blast/guest/{id}', [WhatsappController::class, 'destroyGuest'])->name('blast.deleteGuest');
            
            // Route API internal untuk Node.js WA Server
            Route::post('/blast/start', [WhatsappController::class, 'startSession'])->name('blast.start');
            Route::post('/blast/send/{invitation_id}', [WhatsappController::class, 'blast'])->name('blast.send');
            Route::post('/wa/logout', [WhatsappController::class, 'logoutSession'])->name('blast.logout');
        });
});

require __DIR__ . '/auth.php';

// ====================================================================
// RUTE UNDANGAN DIGITAL (GUEST VIEW & RSVP)
// PERINGATAN: Rute wildcard seperti '/{slug}' WAJIB selalu berada di baris paling bawah!
// ====================================================================
Route::post('/{slug}/rsvp', [FrontController::class, 'storeRsvp'])->name('rsvp.store');
Route::get('/{slug}', [FrontController::class, 'showInvitation'])->name('invitation.show');

// Endpoint API untuk Scanner Buku Tamu
Route::post('/checkin/{slug}', [AttendanceController::class, 'storeCheckIn'])->name('attendance.checkin');
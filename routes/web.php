<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\MusicController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\DashboardController as CustomerDashboard;
use App\Http\Controllers\Client\InvitationController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('welcome');
Route::get('/katalog-tema', [HomeController::class, 'katalog'])->name('katalog'); // RUTE BARU

// ==========================================
// GOOGLE AUTH (Taruh di atas agar tidak bentrok dengan slug)
// ==========================================
Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);
Route::get('/check-slug', [InvitationController::class, 'checkSlug'])->name('check.slug');
// Grup besar: Harus Login & Email Verified
Route::middleware(['auth', 'verified'])->group(function () {

    // ==========================================
    // TRAFFIC CONTROLLER (PENGATUR ARAH OTOMATIS)
    // ==========================================
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
            Route::resource('packages', PackageController::class)->except(['show']);
            Route::resource('categories', CategoryController::class)->except(['show']);
            Route::resource('templates', TemplateController::class)->except(['show']);
            Route::resource('musics', MusicController::class)->except(['show']);

            // Profile Admin
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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

            // Profile Customer
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

            // Di dalam Grup Middleware Customer:
            Route::post('/checkout/{invitation_id}', [CheckoutController::class, 'process'])->name('customer.checkout');

            // DIHAPUS: Route showInvitation tidak boleh ada di sini karena ini area rahasia (wajib login)
        });
});

require __DIR__ . '/auth.php';

// ====================================================================
// RUTE UNDANGAN DIGITAL (GUEST VIEW & RSVP)
// PERINGATAN: Rute wildcard seperti '/{slug}' WAJIB selalu berada di baris paling bawah!
// ====================================================================

// Rute untuk menangani submit form RSVP dari tamu
Route::post('/{slug}/rsvp', [FrontController::class, 'storeRsvp'])->name('rsvp.store');

// Rute untuk menampilkan halaman undangan untuk dibagikan (Publik)
Route::get('/{slug}', [FrontController::class, 'showInvitation'])->name('invitation.show');

// DI LUAR GRUP MIDDLEWARE (Bebas Login, karena ini dipanggil oleh server Midtrans)
Route::post('/midtrans/callback', [CheckoutController::class, 'callback'])->name('midtrans.callback');

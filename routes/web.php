<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\MusicController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Client\DashboardController as CustomerDashboard;
use App\Http\Controllers\Client\InvitationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('welcome');

// Grup besar: Harus Login & Email Verified
Route::middleware(['auth', 'verified'])->group(function () {
    // ==========================================
    // TRAFFIC CONTROLLER (PENGATUR ARAH OTOMATIS)
    // ==========================================
    // Menangkap semua redirect default Breeze ke 'dashboard'
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
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        });

    // ==========================================
    // AREA CUSTOMER (Hanya bisa diakses role: 'client')
    // ==========================================
    // Contoh Grup Route Klien
    Route::middleware(['auth', 'role:client'])
        ->prefix('customer')
        ->name('customer.')
        ->group(function () {
            // Route Dashboard (Yang sebelumnya kita buat)
            Route::get('/dashboard', [CustomerDashboard::class, 'index'])->name('dashboard');

            // ROUTE UNDANGAN SAYA (Tambahkan baris ini)
            Route::resource('invitations', InvitationController::class);
            Route::post('invitations/{id}/gallery', [InvitationController::class, 'uploadGallery'])->name('invitations.gallery.upload');
            Route::delete('gallery/{id}', [InvitationController::class, 'deleteGallery'])->name('invitations.gallery.delete');
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        });

    // ==========================================
    // PROFILE (Bisa diakses keduanya)
    // ==========================================
});

Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

require __DIR__ . '/auth.php';

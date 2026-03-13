<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\MusicController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;
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
        });

    // ==========================================
    // AREA CUSTOMER (Hanya bisa diakses role: 'client')
    // ==========================================
    Route::middleware(['role:client'])
        ->prefix('customer')
        ->name('customer.')
        ->group(function () {
            Route::get('/dashboard', [CustomerDashboard::class, 'index'])->name('dashboard');
        });

    // ==========================================
    // PROFILE (Bisa diakses keduanya)
    // ==========================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

require __DIR__ . '/auth.php';

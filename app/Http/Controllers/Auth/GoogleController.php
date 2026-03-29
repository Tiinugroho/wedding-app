<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Mengarahkan user ke halaman login Google
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Menangkap data balasan dari Google setelah user memilih akun
     */
    public function callback()
    {
        try {
            // SANGAT PENTING: Tambahkan stateless() untuk mencegah error Session State Mismatch
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Cek apakah user sudah terdaftar berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->getId())
                        ->orWhere('email', $googleUser->getEmail())
                        ->first();

            if (!$user) {
                // JIKA USER BARU: Daftarkan secara otomatis
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    // Password acak sangat penting agar tidak ditolak oleh database
                    'password' => bcrypt(Str::random(16)), 
                    'role' => 'client', // Default role untuk pelanggan
                ]);
            } else {
                // JIKA SUDAH ADA: Update google_id & avatar agar sinkron
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            }

            // Login-kan user ke dalam sistem Laravel (dengan parameter "Remember Me" bernilai true)
            Auth::login($user, true);

            // Arahkan ke dashboard yang sesuai dengan rolenya
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('customer.dashboard');

        } catch (Exception $e) {
            // LOG ERROR: Ini agar kamu bisa mengecek storage/logs/laravel.log jika masih gagal
            Log::error('Google Login Error: ' . $e->getMessage());
            
            // Kembalikan ke halaman login dengan pesan error
            return redirect('/login')->withErrors(['login_id' => 'Login dengan Google gagal. Silakan coba lagi.']);
        }
    }
}
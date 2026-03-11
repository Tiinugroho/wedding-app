<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

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
            // Ambil data user dari Google
            $googleUser = Socialite::driver('google')->user();

            // Cek apakah user sudah terdaftar berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->getId())
                        ->orWhere('email', $googleUser->getEmail())
                        ->first();

            if (! $user) {
                // Jika belum ada, daftarkan user baru secara otomatis
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => bcrypt(Str::random(16)), // Password acak karena login via Google
                    'role' => 'client', // Default role untuk klien
                ]);
            } else {
                // Jika sudah ada, update google_id & avatar agar sinkron
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            }

            // Login-kan user ke dalam sistem Laravel
            Auth::login($user);

            // Arahkan ke dashboard yang sesuai dengan rolenya
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('customer.dashboard');

        } catch (\Exception $e) {
            // Jika gagal (misal batal login atau koneksi putus), kembalikan ke halaman login dengan pesan error
            // dd($e->getMessage());
            return redirect('/login')->withErrors(['login_id' => 'Login dengan Google gagal. Silakan coba lagi.']);
        }
    }
}
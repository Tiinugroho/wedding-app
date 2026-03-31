<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // [PERBAIKAN] Definisikan variabel $user dari request
        $user = $request->user();

        // Cek role user dan arahkan ke dashboard yang sesuai
        $url = '';
        if ($user->role === 'admin') {
            $url = route('admin.dashboard', absolute: false);
        } elseif ($user->role === 'client') { // di DB kita menggunakan 'client'
            $url = route('customer.dashboard', absolute: false);
        } else {
            $url = '/'; // Fallback
        }

        // [PERBAIKAN] Menggunakan variabel $user yang sudah didefinisikan di atas dan merapikan spasi
        return redirect()->intended($url)->with('success', 'Selamat Datang, ' . $user->name);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda Telah Logout, Terimakasih!');
    }
}
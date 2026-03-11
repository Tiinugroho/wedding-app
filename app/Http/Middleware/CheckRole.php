<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect('login');
        }

        // Cek apakah role user sesuai dengan role yang diizinkan di Route
        if ($request->user()->role !== $role) {
            
            // Jika admin nyasar ke halaman customer, kembalikan ke admin
            if ($request->user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            
            // Jika customer nyasar ke halaman admin, kembalikan ke customer
            if ($request->user()->role === 'client') {
                return redirect()->route('customer.dashboard');
            }

            // Fallback keamanan jika role tidak dikenali
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
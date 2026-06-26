<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil undangan milik user dengan relasi template dan orders.package
        $invitations = Invitation::with(['template', 'orders.package'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // HITUNG STATISTIK GLOBAL KLIEN
        $totalInvitations = $invitations->count(); 
        
        // Ambil semua paket aktif
        $packages = \App\Models\Package::where('is_active', true)->orderBy('price', 'asc')->get();

        return view('customer.dashboard', compact(
            'invitations', 
            'totalInvitations',
            'packages'
        ));
    }
}
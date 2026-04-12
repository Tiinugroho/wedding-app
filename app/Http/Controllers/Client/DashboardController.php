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

        // Ambil undangan milik user dengan relasi template saja (RSVP dihapus)
        $invitations = Invitation::with(['template'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // 🔥 HITUNG STATISTIK GLOBAL KLIEN 🔥

        // 1. Total Undangan yang dibuat
        $totalInvitations = $invitations->count(); 

        return view('customer.dashboard', compact(
            'invitations', 
            'totalInvitations'
        ));
    }
}
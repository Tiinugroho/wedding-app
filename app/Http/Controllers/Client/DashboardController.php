<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\Attendance; // 🔥 Wajib di-import untuk menghitung total check-in
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil undangan milik user dengan relasi yang diperlukan
        $invitations = Invitation::with(['template', 'rsvps'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // 🔥 HITUNG STATISTIK GLOBAL KLIEN 🔥

        // 1. Total Undangan yang dibuat
        $totalInvitations = $invitations->count(); 

        // 2. Total Tamu Hadir (Check-In via Scanner QR)
        $invitationIds = $invitations->pluck('id');
        $totalCheckIn = Attendance::whereIn('invitation_id', $invitationIds)->count();

        // 3. Total RSVP (Konfirmasi Kehadiran & Buku Tamu)
        $totalRsvp = $invitations->sum(function($invitation) {
            return $invitation->rsvps->count();
        });

        return view('customer.dashboard', compact(
            'invitations', 
            'totalInvitations', 
            'totalCheckIn', 
            'totalRsvp'
        ));
    }
}
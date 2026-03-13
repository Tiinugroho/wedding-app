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

        // Ambil undangan milik user dengan relasi yang diperlukan
        $invitations = Invitation::with(['template', 'rsvps'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // HITUNG STATISTIK KUMULATIF
        // 1. Total Pengunjung (Asumsi ada kolom views di tabel invitations)
        $totalViews = $invitations->sum('views'); 

        // 2. Total RSVP (Menghitung jumlah baris di relasi rsvps)
        $totalRsvp = $invitations->sum(function($invitation) {
            return $invitation->rsvps->count();
        });

        // 3. Total Ucapan (Jika ucapan disimpan di tabel rsvps atau tabel khusus wishes)
        // Di sini saya contohkan menghitung dari rsvps yang ada pesannya
        $totalWishes = $invitations->sum(function($invitation) {
            return $invitation->rsvps->whereNotNull('message')->count();
        });

        return view('customer.dashboard', compact(
            'invitations', 
            'totalViews', 
            'totalRsvp', 
            'totalWishes'
        ));
    }
}
<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil undangan HANYA milik user yang sedang login
        // Load juga relasi template dan rsvps agar bisa dihitung jumlah tamunya
        $invitations = Invitation::with(['template', 'rsvps'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // Kirim data ke view
        return view('dashboard', compact('invitations'));
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Invitation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil data statistik ringkas
        $totalClients = User::where('role', 'client')->count();
        
        // Hitung total pendapatan dari order yang sukses
        $totalRevenue = Order::where('status', 'success')->sum('amount');
        
        // Jumlah undangan yang sedang aktif
        $activeInvitations = Invitation::where('status', 'active')->count();

        // 2. Ambil 5 pesanan terbaru untuk ditampilkan di tabel dashboard
        $recentOrders = Order::with(['user', 'invitation.template'])
            ->latest()
            ->take(5)
            ->get();

        // 3. Kirim data ke view
        return view('admin.dashboard', compact(
            'totalClients', 
            'totalRevenue', 
            'activeInvitations', 
            'recentOrders'
        ));
    }
}
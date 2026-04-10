<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar riwayat pembayaran milik klien yang sedang login.
     */
    public function index()
    {
        // Ambil semua order milik user yang login, urutkan dari yang terbaru
        $orders = Order::with(['package', 'invitation'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Opsional: Untuk menampilkan detail invoice (jika suatu saat dibutuhkan)
     */
    public function show($id)
    {
        $order = Order::with(['package', 'invitation'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('customer.orders.show', compact('order'));
    }
}
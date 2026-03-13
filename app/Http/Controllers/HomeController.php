<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Template;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Ambil 3 template yang sedang aktif untuk di-preview di halaman depan
        // Menggunakan limit 3 agar tampilan grid-nya rapi (bisa disesuaikan)
        $templates = Template::where('is_active', true)
                             ->latest()
                             ->take(3)
                             ->get();

        // 2. Ambil semua paket harga (Dasar, Premium, Eksklusif)
        $packages = Package::where('is_active', true)
                           ->orderBy('price', 'asc') // Urutkan dari harga termurah
                           ->get();

        // 3. Kirim kedua data tersebut ke view 'welcome'
        return view('welcome', compact('templates', 'packages'));
    }
}
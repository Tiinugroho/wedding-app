<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Template;
use App\Models\Category; // Wajib di-import untuk filter katalog
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman depan (Landing Page / Welcome)
     */
    public function index()
    {
        // 1. Ambil semua paket harga yang aktif, urutkan dari termurah
        $packages = Package::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();

        // 2. Ambil 3 template terbaru beserta kategorinya untuk teaser di halaman depan
        $templates = Template::with('category')
            ->where('is_active', true)
            ->latest()
            ->take(3)
            ->get();

        // 3. Kirim data ke view 'welcome'
        return view('welcome', compact('packages', 'templates'));
    }

    /**
     * Menampilkan halaman khusus Katalog Tema
     */
    public function katalog()
    {
        // 1. Ambil semua kategori tema untuk tombol filter
        $categories = Category::orderBy('name', 'asc')->get();
        
        // 2. Ambil SEMUA template yang aktif beserta kategorinya
        $templates = Template::with('category')
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();
        
        // 3. Kirim data ke view 'katalog'
        return view('katalog', compact('categories', 'templates'));
    }
}
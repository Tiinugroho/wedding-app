<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Template;
use App\Models\Music;
use App\Models\Package;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =========================================================
        // 1. SEEDER AKUN USER & ADMIN
        // =========================================================
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@ruangrestu.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Klien Test',
            'email' => 'client@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);

        // =========================================================
        // 2. SEEDER KATEGORI TEMPLATE
        // =========================================================
        $catModern = Category::create(['name' => 'Modern Elegant', 'slug' => 'modern-elegant']);
        $catMinimalist = Category::create(['name' => 'Minimalist', 'slug' => 'minimalist']);

        // =========================================================
        // 3. SEEDER MUSIK LATAR (Ditambah Kolom Kategori)
        // =========================================================
        Music::create([
            'title' => 'A Thousand Years - Christina Perri', 
            'category' => 'Romantis', // <-- Perbaikan: Tambah Kategori
            'file_path' => 'musics/a-thousand-years.mp3'
        ]);
        
        Music::create([
            'title' => 'Beautiful In White - Westlife', 
            'category' => 'Romantis', // <-- Perbaikan: Tambah Kategori
            'file_path' => 'musics/beautiful-in-white.mp3'
        ]);

        Music::create([
            'title' => 'Janji Suci - Yovie & Nuno', 
            'category' => 'Pop', // <-- Contoh tambahan kategori berbeda
            'file_path' => 'musics/janji-suci.mp3'
        ]);

        // =========================================================
        // 4. SEEDER TEMPLATE DINAMIS (Menggunakan Kode Tema 'wed-X')
        // =========================================================
        Template::create([
            'category_id' => $catModern->id,
            'name' => 'Luxury Gold',
            'price' => 0, 
            'view_path' => 'wed-1', // <-- Perbaikan: Gunakan Kode Tema
            'thumbnail' => 'thumbnails/luxury-gold.jpg',
            'is_active' => true,
            'required_fields' => [
                'has_video' => true, 
                'has_love_story' => true, 
                'gallery_limit' => 10
            ],
        ]);

        Template::create([
            'category_id' => $catMinimalist->id,
            'name' => 'Clean White',
            'price' => 0,
            'view_path' => 'wed-2', // <-- Perbaikan: Gunakan Kode Tema
            'thumbnail' => 'thumbnails/clean-white.jpg',
            'is_active' => true,
            'required_fields' => [
                'has_video' => false, 
                'has_love_story' => false, 
                'gallery_limit' => 4
            ],
        ]);

        // =========================================================
        // 5. SEEDER PAKET HARGA (Format Array Murni)
        // =========================================================
        
        // --- PAKET 1: BASIC ---
        Package::create([
            'name' => 'BASIC',
            'price' => 49000, 
            'description' => 'Paket hemat untuk kebutuhan undangan digital yang simpel dan elegan.',
            'features' => [
                'included' => [
                    'Masa Aktif 1 Bulan',
                    'Share dan Buku Tamu Unlimited',
                    'Bebas Memilih Tema (50+ Pilihan)',
                    'Bebas Memilih Musik (250+ Pilihan)',
                    'Countdown Acara',
                    'Google Map Lokasi Acara',
                    'Galeri 5 Foto',
                    'Bisa Input 2 Jenis Acara di 1 Undangan',
                ],
                'excluded' => [
                    'Auto Blast Smart Whatsapp',
                    'Buku Tamu Dengan QR Code',
                    'Fitur Kirim Amplop Untuk Mempelai',
                    'Fitur Love Story Mempelai',
                    'Voice Comment untuk Tamu',
                    'Custom Nama Domain Sendiri (.com/.id)',
                    'Tanpa Watermark RuangRestu'
                ]
            ],
        ]);

        // --- PAKET 2: PLATINUM ---
        Package::create([
            'name' => 'PLATINUM',
            'price' => 76000,          
            'original_price' => 149999, 
            'description' => 'Paket terpopuler dengan fitur interaktif lengkap untuk para tamu.',
            'features' => [
                'included' => [
                    'Masa Aktif 3 Bulan',
                    'Share dan Buku Tamu Unlimited',
                    'Auto Blast Smart Whatsapp 200 Tamu',
                    'Bebas Memilih Tema (50+ Pilihan)',
                    'Bebas Memilih Musik (250+ Pilihan)',
                    'Bebas Custom Font Tema',
                    'Autoplay Musik',
                    'Countdown Acara',
                    'Buku Tamu Dengan QR Code',
                    'Google Map Lokasi Acara',
                    'Bebas Custom Quotes di Undangan',
                    'Bisa Input Link Streaming Youtube, Instagram & Tiktok',
                    'Custom Preview Whatsapp Saat Share Undangan',
                    'Fitur love story mempelai',
                    'Galeri 9 Foto',
                    'Fitur Kirim Amplop Untuk Mempelai',
                    'Bisa Input 3 Jenis Acara di 1 Undangan',
                    'Voice Comment untuk Tamu'
                ],
                'excluded' => [
                    'Custom Nama Domain Sendiri (.com/.id)',
                    'Tanpa Watermark RuangRestu'
                ] 
            ],
        ]);

        // --- PAKET 3: PRIORITY ---
        Package::create([
            'name' => 'PRIORITY',
            'price' => 149000, 
            'description' => 'Masa aktif tanpa batas dengan prioritas layanan khusus.',
            'features' => [
                'included' => [
                    'Masa Aktif Selamanya (Unlimited)',
                    'Share dan Buku Tamu Unlimited',
                    'Auto Blast Smart Whatsapp 1000 Tamu',
                    'Bebas Memilih Tema (50+ Pilihan)',
                    'Bebas Memilih Musik (250+ Pilihan)',
                    'Bebas Custom Font Tema',
                    'Autoplay Musik',
                    'Countdown Acara',
                    'Buku Tamu Dengan QR Code',
                    'Google Map Lokasi Acara',
                    'Bebas Custom Quotes di Undangan',
                    'Bisa Input Link Streaming Youtube, Instagram & Tiktok',
                    'Custom Preview Whatsapp Saat Share Undangan',
                    'Fitur love story mempelai',
                    'Galeri 20 Foto & 2 Video',
                    'Fitur Kirim Amplop Untuk Mempelai',
                    'Bisa Input 5 Jenis Acara di 1 Undangan',
                    'Voice Comment untuk Tamu',
                    'Custom Nama Domain Sendiri (.com/.id)',
                    'Tanpa Watermark RuangRestu'
                ],
                'excluded' => []
            ],
        ]);
    }
}
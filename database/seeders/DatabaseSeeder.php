<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\Category;
use App\Models\Music;
use App\Models\Package;
use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
        // 3. SEEDER MUSIK LATAR (Disesuaikan dengan opsi Dropdown)
        // =========================================================

        Music::create([
            'title' => 'Janji Suci - Yovie & Nuno',
            'category' => 'Musik Indonesia',
        ]);

        Music::create([
            'title' => 'Gamelan Jawa Wedding (Panggih)',
            'category' => 'Musik Traditional',
        ]);

        Music::create([
            'title' => 'Kiroro - Mirai e',
            'category' => 'Musik Jepang',
        ]);

        Music::create([
            'title' => 'Canon in D - Pachelbel (Piano)',
            'category' => 'Musik Instrumental',
        ]);

        Music::create([
            'title' => 'Barakallah - Maher Zain',
            'category' => 'Musik Islami',
        ]);

        Music::create([
            'title' => 'A Thousand Years - Christina Perri',
            'category' => 'Musik Barat',
        ]);

        Music::create([
            'title' => 'Beautiful In White - Westlife',
            'category' => 'Musik Barat',
        ]);

        Music::create([
            'title' => 'Marry You - Bruno Mars',
            'category' => 'Musik Celebration',
        ]);

        // =========================================================
        // 4. SEEDER TEMPLATE DINAMIS
        // =========================================================
        Template::create([
            'category_id' => $catModern->id,
            'name' => 'Luxury Gold',
            'price' => 0,
            'view_path' => 'template1',
            'thumbnail' => 'thumbnails/luxury-gold.jpg',
            'is_active' => true,
            'required_fields' => [
                'has_video' => true,
                'has_love_story' => true,
                'gallery_limit' => 10,
            ],
        ]);

        Template::create([
            'category_id' => $catMinimalist->id,
            'name' => 'Clean White',
            'price' => 0,
            'view_path' => 'template2',
            'thumbnail' => 'thumbnails/clean-white.jpg',
            'is_active' => true,
            'required_fields' => [
                'has_video' => false,
                'has_love_story' => false,
                'gallery_limit' => 4,
            ],
        ]);

        // =========================================================
        // 5. SEEDER PAKET HARGA (Dengan Logika Fitur)
        // =========================================================

        // --- PAKET 1: BASIC ---
        Package::create([
            'name' => 'BASIC',
            'price' => 49000,
            'description' => 'Paket hemat untuk kebutuhan undangan digital yang simpel.',
            'features' => [
                'display' => [
                    'included' => ['Masa Aktif 1 Bulan', 'Galeri 5 Foto', 'Bisa Input 2 Acara'],
                    'excluded' => ['Fitur Love Story', 'Kado Digital / Amplop', 'Video Galeri'],
                ],
                'logic' => [
                    'event_limit' => 2,
                    'gallery_limit' => 5,
                    'has_love_story' => false,
                    'has_digital_gift' => false,
                    'has_video' => false,
                ],
            ],
        ]);

        // --- PAKET 2: PLATINUM ---
        Package::create([
            'name' => 'PLATINUM',
            'price' => 76000,
            'original_price' => 149999,
            'description' => 'Paket terpopuler dengan fitur interaktif lengkap untuk tamu.',
            'features' => [
                'display' => [
                    'included' => ['Masa Aktif 3 Bulan', 'Galeri 9 Foto', 'Fitur Love Story', 'Kado Digital / Amplop'],
                    'excluded' => ['Video Galeri'],
                ],
                'logic' => [
                    'event_limit' => 3,
                    'gallery_limit' => 9,
                    'has_love_story' => true,
                    'has_digital_gift' => true,
                    'has_video' => false,
                ],
            ],
        ]);

        // --- PAKET 3: PRIORITY ---
        Package::create([
            'name' => 'PRIORITY',
            'price' => 149000,
            'description' => 'Masa aktif tanpa batas dengan prioritas layanan khusus.',
            'features' => [
                'display' => [
                    'included' => ['Masa Aktif Selamanya', 'Galeri 20 Foto & 2 Video', 'Semua Fitur Terbuka'],
                    'excluded' => [],
                ],
                'logic' => [
                    'event_limit' => 5,
                    'gallery_limit' => 20,
                    'has_love_story' => true,
                    'has_digital_gift' => true,
                    'has_video' => true,
                ],
            ],
        ]);
        // Tambahkan Use di atas: use App\Models\Bank;

        Bank::create(['name' => 'BCA', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg']);
        Bank::create(['name' => 'Mandiri', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg']);
        Bank::create(['name' => 'BNI', 'logo' => 'https://upload.wikimedia.org/wikipedia/id/5/55/BNI_logo.svg']);
        Bank::create(['name' => 'BRI', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/9/9e/BRI_2020.svg']);
        Bank::create(['name' => 'DANA', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg']);
        Bank::create(['name' => 'OVO', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/e/e1/Logo_OVO.svg']);
        Bank::create(['name' => 'GoPay', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/8/86/Gopay_logo.svg']);
        Bank::create(['name' => 'ShopeePay', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/f/fe/ShopeePay_Logo.png']);
    }
}

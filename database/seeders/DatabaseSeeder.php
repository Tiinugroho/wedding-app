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
        // 3. SEEDER MUSIK LATAR
        // =========================================================
        Music::insert([
            ['title' => 'Janji Suci - Yovie & Nuno', 'category' => 'Musik Indonesia'],
            ['title' => 'Gamelan Jawa Wedding (Panggih)', 'category' => 'Musik Traditional'],
            ['title' => 'Kiroro - Mirai e', 'category' => 'Musik Jepang'],
            ['title' => 'Canon in D - Pachelbel (Piano)', 'category' => 'Musik Instrumental'],
            ['title' => 'Barakallah - Maher Zain', 'category' => 'Musik Islami'],
            ['title' => 'A Thousand Years - Christina Perri', 'category' => 'Musik Barat'],
            ['title' => 'Beautiful In White - Westlife', 'category' => 'Musik Barat'],
            ['title' => 'Marry You - Bruno Mars', 'category' => 'Musik Celebration'],
        ]);

        // =========================================================
        // 4. SEEDER PAKET HARGA (Berbasis Masa Aktif, SEMUA FITUR TERBUKA)
        // (Dipindah ke atas agar ID-nya bisa dipakai oleh Template)
        // =========================================================

        // --- PAKET 1: PREMIUM (1 BULAN) ---
        $pkgPremium = Package::create([
            'name' => 'PREMIUM',
            'price' => 89000,
            'description' => 'Paket hemat dengan semua fitur premium terbuka, masa aktif 1 Bulan.',
            'features' => [
                'display' => [
                    'included' => ['Semua Fitur Premium Terbuka', 'Galeri 10 Foto', 'Fitur Love Story & Amplop Digital', 'Masa Aktif 1 Bulan'],
                    'excluded' => [], // Tidak ada yang dilimit fiturnya
                ],
                'logic' => [
                    'event_limit' => 3,
                    'gallery_limit' => 10,
                    'has_love_story' => true,
                    'has_digital_gift' => true,
                    'has_video' => true,
                    'active_days' => 30, // Masa aktif dalam hari
                ],
            ],
        ]);

        // =========================================================
        // 5. SEEDER TEMPLATE DINAMIS
        // =========================================================
        Template::create([
            'category_id' => $catModern->id,
            'package_id' => $pkgPremium->id, // Menggunakan package_id pengganti price
            'name' => 'Luxury Gold',
            'view_path' => 't1',
            'thumbnail' => null,
            'is_active' => true,
            'required_fields' => [
                'has_video' => true,
                'has_love_story' => true,
                'gallery_limit' => 10,
            ],
        ]);

        Template::create([
            'category_id' => $catMinimalist->id,
            'package_id' => $pkgPremium->id, // Menggunakan package_id pengganti price
            'name' => 'Clean White',
            'view_path' => 't2',
            'thumbnail' => null,
            'is_active' => true,
            'required_fields' => [
                'has_video' => false,
                'has_love_story' => false,
                'gallery_limit' => 10,
            ],
        ]);

        // =========================================================
        // 6. SEEDER BANK / E-WALLET
        // =========================================================
        Bank::insert([
            ['name' => 'BCA', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg', 'is_active' => true],
            ['name' => 'Mandiri', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg', 'is_active' => true],
            ['name' => 'BNI', 'logo' => 'https://upload.wikimedia.org/wikipedia/id/5/55/BNI_logo.svg', 'is_active' => true],
            ['name' => 'BRI', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/9/9e/BRI_2020.svg', 'is_active' => true],
            ['name' => 'BSI', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a0/Bank_Syariah_Indonesia.svg/1024px-Bank_Syariah_Indonesia.svg.png', 'is_active' => true],
            ['name' => 'DANA', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg', 'is_active' => true],
            ['name' => 'OVO', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/e/e1/Logo_OVO.svg', 'is_active' => true],
            ['name' => 'GoPay', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/8/86/Gopay_logo.svg', 'is_active' => true],
            ['name' => 'ShopeePay', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/f/fe/ShopeePay_Logo.png', 'is_active' => true],
        ]);
    }
}
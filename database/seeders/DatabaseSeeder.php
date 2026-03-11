<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Template;
use App\Models\Music;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seeder Akun User & Admin
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

        // 2. Seeder Kategori Template
        $catModern = Category::create(['name' => 'Modern Elegant', 'slug' => 'modern-elegant']);
        $catMinimalist = Category::create(['name' => 'Minimalist', 'slug' => 'minimalist']);

        // 3. Seeder Musik Latar
        Music::create([
            'title' => 'A Thousand Years - Christina Perri',
            'file_path' => 'musics/a-thousand-years.mp3',
        ]);

        Music::create([
            'title' => 'Beautiful In White - Westlife',
            'file_path' => 'musics/beautiful-in-white.mp3',
        ]);

        // 4. Seeder Template Dinamis (Perhatikan JSON required_fields-nya)

        // Template A: Punya fitur lengkap (Premium)
        Template::create([
            'category_id' => $catModern->id,
            'name' => 'Luxury Gold',
            'price' => 150000,
            'view_path' => 'themes.luxury_gold.index',
            'thumbnail' => 'thumbnails/luxury-gold.jpg',
            'is_active' => true,
            'required_fields' => [
                'has_video' => true,
                'has_love_story' => true,
                'has_quote' => true,
                'gallery_limit' => 10,
            ], // Ini akan otomatis di-cast menjadi JSON oleh Model
        ]);

        // Template B: Simple & Minimalis (Basic)
        Template::create([
            'category_id' => $catMinimalist->id,
            'name' => 'Clean White',
            'price' => 75000,
            'view_path' => 'themes.clean_white.index',
            'thumbnail' => 'thumbnails/clean-white.jpg',
            'is_active' => true,
            'required_fields' => [
                'has_video' => false,
                'has_love_story' => false,
                'has_quote' => true,
                'gallery_limit' => 4,
            ],
        ]);
    }
}

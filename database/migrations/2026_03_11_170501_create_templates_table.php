<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            // Relasi ke Kategori
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            // Relasi ke Paket (Harga mengikuti paket ini)
            $table->foreignId('package_id')->constrained()->cascadeOnDelete(); 
            
            $table->string('name');
            $table->string('view_path');
            $table->string('thumbnail')->nullable();
            
            // Konfigurasi dinamis (Bisa diisi null jika semua fitur sepenuhnya ikut paket)
            $table->json('required_fields')->nullable(); 
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
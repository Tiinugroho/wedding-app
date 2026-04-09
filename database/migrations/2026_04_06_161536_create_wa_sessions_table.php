<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wa_sessions', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel users dengan cascade delete
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Session ID unik untuk engine Node.js
            $table->string('session_id')->unique();
            
            // Nomor WA yang terhubung (opsional)
            $table->string('wa_number')->nullable();
            
            // Status koneksi menggunakan ENUM
            $table->enum('status', ['disconnected', 'qr_ready', 'connected'])->default('disconnected');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wa_sessions');
    }
};
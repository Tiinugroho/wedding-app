<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: Paket Dasar, Premium, Eksklusif
            $table->bigInteger('price'); // Harga paket
            $table->text('description')->nullable();
            $table->bigInteger('original_price')->nullable();

            // Kolom JSON ini super penting untuk membatasi fitur (misal: max foto, bisa video/tidak)
            $table->json('features')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};

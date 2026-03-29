<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invitation_id')->constrained()->cascadeOnDelete();
            $table->bigInteger('amount');
            
            // KOLOM KHUSUS DUITKU
            $table->string('reference')->nullable(); // Menyimpan ID Transaksi dari Duitku
            $table->string('payment_url')->nullable(); // Menyimpan Link Checkout Duitku
            
            $table->enum('status', ['pending', 'success', 'failed', 'expired'])->default('pending');
            $table->foreignId('package_id')
                  ->nullable()
                  ->constrained('packages')
                  ->onDelete('set null'); // Jika paket dihapus, order tidak ikut terhapus
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
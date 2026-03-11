<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitation_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_id')->constrained()->cascadeOnDelete();
            $table->json('content'); // Penyimpan teks dinamis
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitation_details');
    }
};
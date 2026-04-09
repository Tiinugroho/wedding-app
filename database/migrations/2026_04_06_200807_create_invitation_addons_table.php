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
        Schema::create('invitation_addons', function (Blueprint $table) {
    $table->id();
    $table->foreignId('invitation_id')->constrained()->onDelete('cascade');
    $table->integer('extra_quota')->default(0); // Jumlah kuota tambahan yang dibeli
    $table->integer('used_quota')->default(0);  // Jumlah yang sudah terpakai dari kuota tambahan ini
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitation_addons');
    }
};

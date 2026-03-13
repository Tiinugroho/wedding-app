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
        Schema::table('musics', function (Blueprint $table) {
            // Menambahkan kolom category setelah title
            $table->string('category')->default('Umum')->after('title')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('musics', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};

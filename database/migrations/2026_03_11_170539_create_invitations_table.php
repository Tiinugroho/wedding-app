<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('template_id')->constrained()->cascadeOnDelete();
            $table->foreignId('music_id')->nullable()->constrained('musics')->nullOnDelete();
            $table->string('slug')->unique();
            $table->string('referral_code')->nullable();
            $table->enum('status', ['draft', 'unpaid', 'active', 'expired'])->default('draft');
            $table->integer('visits_count')->default(0);
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};

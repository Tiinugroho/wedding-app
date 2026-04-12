<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invitation;
use Carbon\Carbon;

class CleanupInvitations extends Command
{
    // Nama command yang akan dijalankan
    protected $signature = 'invitations:cleanup';
    protected $description = 'Hapus undangan belum lunas >7 hari dan nonaktifkan yang lewat masa aktif (14/30 hari)';

    public function handle()
    {
        // 1. HAPUS UNDANGAN DRAFT/UNPAID > 7 HARI
        // Karena ada relasi (Guests, Galleries, dll) di DB, pastikan Migration pakai "ON DELETE CASCADE"
        $deletedCount = Invitation::whereIn('status', ['draft', 'unpaid'])
            ->where('created_at', '<=', Carbon::now()->subDays(7))
            ->delete();

        $this->info("Berhasil menghapus {$deletedCount} undangan yang belum dibayar lebih dari 7 hari.");

        // 2. UBAH STATUS JADI EXPIRED UNTUK YANG SUDAH LEWAT MASA AKTIF (14/30 HARI)
        $expiredCount = Invitation::where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', Carbon::now())
            ->update(['status' => 'expired']);

        $this->info("Berhasil mengubah status {$expiredCount} undangan menjadi expired.");
    }
}
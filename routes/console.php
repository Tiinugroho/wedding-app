<?php
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Storage;
use App\Models\Invitation;

Schedule::call(function () {
    // 1. Cari undangan status 'draft' yang dibuat lebih dari 7 hari yang lalu
    $abandonedInvitations = Invitation::with('galleries')
        ->where('status', 'draft')
        ->where('created_at', '<', now()->subDays(7))
        ->get();

    foreach ($abandonedInvitations as $invitation) {
        // 2. Hapus file fisik di Storage agar disk server tidak penuh
        foreach ($invitation->galleries as $gallery) {
            if (Storage::disk('public')->exists($gallery->file_path)) {
                Storage::disk('public')->delete($gallery->file_path);
            }
        }
        
        // Opsional: Hapus folder galeri undangan tersebut jika kosong
        Storage::disk('public')->deleteDirectory('galleries/' . $invitation->id);

        // 3. Hapus data dari Database (Ini otomatis menghapus detail, order, dll jika on delete cascade sudah di-set di tabel)
        $invitation->delete();
    }
})->dailyAt('02:00'); // Dijalankan setiap jam 2 pagi

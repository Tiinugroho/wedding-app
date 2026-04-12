<?php
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Storage;
use App\Models\Invitation;

Schedule::call(function () {
    // 1. HAPUS UNDANGAN DRAFT / BELUM LUNAS > 7 HARI
    $abandonedInvitations = Invitation::with('galleries')
        ->whereIn('status', ['draft', 'unpaid'])
        ->where('created_at', '<', now()->subDays(7))
        ->get();

    foreach ($abandonedInvitations as $invitation) {
        // Hapus file fisik di Storage agar disk server tidak penuh
        foreach ($invitation->galleries as $gallery) {
            if (Storage::disk('public')->exists($gallery->file_path)) {
                Storage::disk('public')->delete($gallery->file_path);
            }
        }
        
        // Hapus folder galeri undangan tersebut jika ada
        Storage::disk('public')->deleteDirectory('galleries/' . $invitation->id);

        // Hapus data dari Database (Ini otomatis menghapus detail, order, dll via cascade)
        $invitation->delete();
    }

    // 2. NONAKTIFKAN UNDANGAN YANG SUDAH LEWAT MASA AKTIF (EXPIRED)
    Invitation::where('status', 'active')
        ->whereNotNull('expires_at')
        ->where('expires_at', '<', now())
        ->update(['status' => 'expired']);

})->dailyAt('02:00'); // Dijalankan setiap jam 2 pagi
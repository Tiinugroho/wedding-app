<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Menampilkan halaman Scanner Kamera untuk Klien (Penerima Tamu)
     */
    public function scanner($id)
    {
        // Pastikan klien hanya bisa membuka scanner undangannya sendiri
        $invitation = Invitation::where('user_id', Auth::id())->findOrFail($id);
        
        // Ambil data tamu yang sudah scan hari ini sebagai riwayat
        $attendances = Attendance::where('invitation_id', $invitation->id)->latest()->get();

        return view('customer.invitations.scanner', compact('invitation', 'attendances'));
    }

    /**
     * Memproses URL hasil scan QR Code dari Javascript (AJAX)
     */
    public function storeCheckIn(Request $request, $slug)
    {
        $invitation = Invitation::where('slug', $slug)->first();

        if (!$invitation) {
            return response()->json(['success' => false, 'message' => 'Undangan tidak valid atau tidak ditemukan!'], 404);
        }

        // Ambil nama dari parameter URL (contoh: ?name=Budi)
        $guestName = $request->query('name', 'Tamu Tanpa Nama');

        // Opsional: Cek agar tidak double scan dalam waktu berdekatan
        $alreadyScanned = Attendance::where('invitation_id', $invitation->id)
                                    ->where('guest_name', $guestName)
                                    ->first();

        if ($alreadyScanned) {
            return response()->json([
                'success' => false, 
                'message' => "Tamu bernama {$guestName} sudah melakukan Check-In sebelumnya."
            ]);
        }

        // Simpan ke database
        Attendance::create([
            'invitation_id' => $invitation->id,
            'guest_name' => $guestName,
            'check_in_time' => now(),
        ]);

        return response()->json([
            'success' => true, 
            'message' => "Berhasil Check-In: {$guestName}",
            'guest_name' => $guestName,
            'time' => now()->format('H:i')
        ]);
    }
}
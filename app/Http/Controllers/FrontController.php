<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Invitation;
use App\Models\WishesRsvp;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function showInvitation(Request $request, $slug)
    {
        $invitation = Invitation::with(['template', 'details', 'galleries', 'music'])
            ->where('slug', $slug)
            ->firstOrFail();

        // =========================================================
        // LOGIKA PROTEKSI UNDANGAN (KUNCI PUBLIK JIKA BELUM LUNAS)
        // =========================================================
        if ($invitation->status !== 'active') {
            // Cek apakah pengunjung saat ini adalah pemilik undangan
            $isOwner = auth()->check() && auth()->id() === $invitation->user_id;
            
            // Jika BUKAN pemilik, tolak aksesnya
            if (!$isOwner) {
                // Kamu bisa mengarahkan ke halaman 403 bawaan Laravel
                abort(403, 'Maaf, undangan ini belum diaktifkan oleh mempelai atau masa berlakunya telah habis.');
            }
        }
        // =========================================================

        $dbContent = json_decode($invitation->details->content ?? '{}', true);
        $previewData = $request->except(['_token', '_method', 'to']); 
        $content = array_merge($dbContent, $previewData);

        // Deteksi Tamu Spesifik dari URL (?to=slug_name)
        $guestSlug = $request->query('to');
        $guestData = null;
        
        if ($guestSlug) {
            $guestData = Guest::where('invitation_id', $invitation->id)
                            ->where('slug_name', $guestSlug)
                            ->first();
        }

        $viewPath = 'template.' . $invitation->template->view_path; 
        
        if (view()->exists($viewPath)) {
            return view($viewPath, compact('invitation', 'content', 'guestData'));
        }

        return "File template {$viewPath} belum tersedia di folder resources/views/template/";
    }

    public function storeRsvp(Request $request, $slug)
    {
        $invitation = Invitation::where('slug', $slug)->firstOrFail();

        // Validasi inputan form dari tamu (Tambahkan validasi guest_id)
        $request->validate([
            'guest_id' => 'nullable|exists:guests,id', // Cek apakah ID tamu valid
            'guest_name' => 'required|string|max:255',
            'pax' => 'required|integer|min:1|max:10',
            'status_rsvp' => 'required|in:hadir,tidak_hadir,ragu', // Sesuai dengan ENUM migration kamu!
            'message' => 'required|string|max:1000',
        ]);

        // Simpan ke database wishes_rsvps
        WishesRsvp::create([
            'invitation_id' => $invitation->id,
            'guest_id' => $request->guest_id, // Simpan ID Tamu jika ada
            'guest_name' => $request->guest_name,
            'status_rsvp' => $request->status_rsvp,
            'pax' => $request->pax,
            'message' => $request->message,
        ]);

        // Jika tamu ini spesifik (punya guest_id) dan statusnya hadir,
        // Kita juga bisa update status 'is_present' di tabel guests menjadi true!
        if ($request->guest_id && $request->status_rsvp === 'hadir') {
            Guest::where('id', $request->guest_id)->update(['is_present' => true]);
        }

        return response()->json([
            'success' => true, 
            'message' => 'Terima kasih atas konfirmasi dan doa restu Anda.'
        ]);
    }
}
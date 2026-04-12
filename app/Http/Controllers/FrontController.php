<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Invitation;
use App\Models\WishesRsvp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FrontController extends Controller
{
    public function showInvitation(Request $request, $slug)
    {
        $invitation = Invitation::with(['template', 'details', 'galleries', 'music'])
            ->where('slug', $slug)
            ->firstOrFail();

        // =========================================================
        // LOGIKA PROTEKSI UNDANGAN (DRAFT & EXPIRED)
        // =========================================================
        $isOwner = auth()->check() && auth()->id() === $invitation->user_id;

        // 1. JIKA UNDANGAN BELUM LUNAS / DRAFT
        if ($invitation->status !== 'active') {
            if (!$isOwner) {
                abort(403, 'Maaf, undangan ini belum diaktifkan oleh mempelai.');
            }
        } 
        // 2. JIKA UNDANGAN SUDAH LUNAS TAPI MASA AKTIF HABIS (EXPIRED)
        else {
            if ($invitation->expires_at && Carbon::now()->greaterThan($invitation->expires_at)) {
                if (!$isOwner) {
                    abort(403, 'Maaf, masa aktif undangan ini telah habis.');
                }
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
        // 1. Cari undangan berdasarkan slug di URL
        $invitation = Invitation::where('slug', $slug)->firstOrFail();

        // 2. Validasi data yang dikirim dari Javascript
        $request->validate([
            'guest_name' => 'required|string|max:255',
            'status_rsvp' => 'required|in:hadir,tidak_hadir,ragu',
            'pax' => 'required|integer|min:0',
            'message' => 'required|string',
        ]);

        // 3. Cek apakah ini tamu VIP (dari tabel guests yang di-blast WA)
        $guest = DB::table('guests')
            ->where('invitation_id', $invitation->id)
            ->where('name', $request->guest_name)
            ->first();

        // 4. Simpan ke database wishes_rsvps
        DB::table('wishes_rsvps')->insert([
            'invitation_id' => $invitation->id,
            'guest_id' => $guest ? $guest->id : null,
            'guest_name' => $request->guest_name,
            'status_rsvp' => $request->status_rsvp,
            'pax' => $request->pax,
            'message' => $request->message,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => 'success', 
            'message' => 'RSVP berhasil disimpan!'
        ]);
    }
}
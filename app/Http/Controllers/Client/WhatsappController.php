<?php

namespace App\Http\Controllers\Client;

use App\Exports\TemplateGuestExport;
use App\Http\Controllers\Controller;
use App\Imports\GuestsImport;
use App\Jobs\SendWaBlastJob;
use App\Models\Guest;
use App\Models\Invitation;
use App\Models\WaSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class WhatsappController extends Controller
{
    public function index($invitation_id)
    {
        // 🔥 PERBAIKAN: Ambil undangan SPESIFIK berdasarkan ID yang dipilih
        // Pastikan juga undangan tersebut benar-benar milik user yang sedang login
        $invitation = Invitation::where('user_id', Auth::id())->findOrFail($invitation_id);
        
        // Ambil data tamu khusus untuk undangan yang dipilih
        $guests = Guest::where('invitation_id', $invitation->id)->orderBy('name', 'asc')->get();
        $sudahDikirim = Guest::where('invitation_id', $invitation->id)->where('is_blasted', 1)->count();

        // ID Sesi WA tetap dibuat per User (bukan per undangan) agar klien tidak perlu scan QR berkali-kali
        $sessionId = 'user_' . Auth::id();
        WaSession::updateOrCreate(['user_id' => Auth::id()], ['session_id' => $sessionId]);

        return view('customer.blast.index', compact('invitation', 'guests', 'sessionId', 'sudahDikirim'));
    }

    // 🔥 FUNGSI BARU UNTUK TAMBAH TAMU MANUAL
    public function storeGuest(Request $request, $invitation_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
        ]);

        $number = $request->phone_number;

        // 🔥 BERSIHKAN & FORMAT NOMOR OTOMATIS
        if (!empty($number)) {
            // Buang semua karakter selain angka (spasi, strip, tanda +)
            $number = preg_replace('/[^0-9]/', '', $number);
            
            // Jika diawali 0 (misal 0812), ganti 0 jadi 62
            if (str_starts_with($number, '0')) {
                $number = '62' . substr($number, 1);
            } 
            // Jika diawali 8 (misal 812), tambahkan 62 di depannya
            elseif (str_starts_with($number, '8')) {
                $number = '62' . $number;
            }
        }

        // 🔥 SIMPAN KE DATABASE
        Guest::create([
            'invitation_id' => $invitation_id,
            'name'          => $request->name,
            // Jika nomor kosong, simpan sebagai NULL agar tidak error
            'phone_number'  => !empty($number) ? $number : null,
            // urlencode membuat spasi menjadi + (Contoh: Jati+Nugroho)
            'slug_name'     => urlencode($request->name),
            'is_present'    => 0,
            'is_blasted'    => 0,
        ]);

        return back()->with('success', 'Tamu berhasil ditambahkan secara manual.');
    }

    public function importExcel(Request $request, $invitation_id)
    {
        // 🔥 PENGAMAN: Cek jika belum punya undangan
        if ($invitation_id == 0) {
            return back()->with('error', 'Gagal mengimpor. Anda harus membuat undangan terlebih dahulu.');
        }

        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        Excel::import(new GuestsImport($invitation_id), $request->file('file_excel'));

        return back()->with('success', 'Daftar tamu berhasil diimpor ke database.');
    }

    public function downloadTemplate()
    {
        // Mengenerate dan langsung mendownload file
        return Excel::download(new TemplateGuestExport, 'Template_Tamu_RuangRestu.xlsx');
    }

    public function startSession()
    {
        $sessionId = 'user_' . Auth::id();

        // 🔥 CEK STATUS DULU
        $status = Http::get("http://127.0.0.1:3000/api/wa/status/$sessionId")->json();

        if ($status['status'] === 'connected' || $status['status'] === 'qr_ready') {
            return response()->json([
                'status' => 'already_running',
            ]);
        }

        $response = Http::post('http://127.0.0.1:3000/api/wa/start', [
            'session_id' => $sessionId,
        ]);

        return response()->json($response->json());
    }

    public function blast(Request $request, $invitation_id)
    {
        $request->validate([
            'message' => 'required|string',
            'guest_ids' => 'required|array',
        ]);

        $invitation = Invitation::findOrFail($invitation_id);
        $guests = Guest::whereIn('id', $request->guest_ids)->whereNotNull('phone_number')->get();
        $sessionId = 'user_' . Auth::id();

        $linkUndangan = url("/{$invitation->slug}");
        $delaySeconds = 0;

        foreach ($guests as $guest) {
            // Dispatch job ke antrean dengan delay bertambah 10 detik setiap pesan
            SendWaBlastJob::dispatch($guest, $request->message, $linkUndangan, $sessionId)->delay(now()->addSeconds($delaySeconds));

            $delaySeconds += 10;
        }

        return back()->with('success', 'Proses pengiriman massal telah dimulai di latar belakang.');
    }

    // 🔥 FUNGSI BARU UNTUK HAPUS TAMU
    public function destroyGuest($id)
    {
        $guest = Guest::findOrFail($id);
        
        // Pastikan tamu yang dihapus benar-benar milik user yang sedang login
        if ($guest->invitation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $guest->delete();

        return back()->with('success', 'Data tamu berhasil dihapus.');
    }

    public function logoutSession()
    {
        $sessionId = 'user_' . Auth::id();

        $response = Http::post('http://127.0.0.1:3000/api/wa/logout', [
            'session_id' => $sessionId,
        ]);

        return response()->json($response->json());
    }
}

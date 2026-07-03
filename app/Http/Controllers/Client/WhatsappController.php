<?php

namespace App\Http\Controllers\Client;

use App\Exports\TemplateGuestExport;
use App\Http\Controllers\Controller;
use App\Imports\GuestsImport;
use App\Jobs\SendWaBlastJob;
use App\Models\Guest;
use App\Models\Invitation;
use App\Models\Order;
use App\Models\WaSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class WhatsappController extends Controller
{
    public function index($invitation_id)
    {
        $invitation = Invitation::where('user_id', Auth::id())->findOrFail($invitation_id);

        if ($invitation->status !== 'active') {
            return redirect()->route('customer.invitations.index')->with('error', 'Maaf, Anda harus mengaktifkan undangan (melakukan pembayaran) sebelum bisa mengakses fitur WhatsApp Blast.');
        }

        // Ambil data tamu khusus untuk undangan yang dipilih
        $guests = Guest::where('invitation_id', $invitation->id)->orderBy('name', 'asc')->get();
        $sudahDikirim = Guest::where('invitation_id', $invitation->id)->where('is_blasted', 1)->count();

        // Ambil limit paket dari order berbayar terakhir yang lunas/success
        $lastPaidOrder = Order::with('package')
            ->where('invitation_id', $invitation->id)
            ->whereNotNull('package_id')
            ->where('status', 'success')
            ->latest()
            ->first();

        $packageName = 'FREE';
        $packageLimit = 0;
        if ($lastPaidOrder && $lastPaidOrder->package) {
            $packageName = $lastPaidOrder->package->name;
            $features = $lastPaidOrder->package->features;
            if (is_string($features)) {
                $features = json_decode($features, true);
            }
            $packageLimit = $features['logic']['blast_limit'] ?? 0;
        }

        $addon = \App\Models\InvitationAddon::where('invitation_id', $invitation->id)->first();
        $extraQuota = $addon ? (int) $addon->extra_quota : 0;

        $totalQuota = $packageLimit + $extraQuota;
        $remainingQuota = max(0, $totalQuota - $sudahDikirim);

        // ID Sesi WA tetap dibuat per User (bukan per undangan) agar klien tidak perlu scan QR berkali-kali
        $sessionId = 'user_' . Auth::id();
        WaSession::updateOrCreate(['user_id' => Auth::id()], ['session_id' => $sessionId]);

        return view('customer.blast.index', compact(
            'invitation',
            'guests',
            'sessionId',
            'sudahDikirim',
            'packageName',
            'packageLimit',
            'extraQuota',
            'totalQuota',
            'remainingQuota'
        ));
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
            'name' => $request->name,
            // Jika nomor kosong, simpan sebagai NULL agar tidak error
            'phone_number' => !empty($number) ? $number : null,
            // urlencode membuat spasi menjadi + (Contoh: Jati+Nugroho)
            'slug_name' => urlencode($request->name),
            'is_present' => 0,
            'is_blasted' => 0,
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

    private function normalizeWaPayload($payload, $fallbackStatus = 'loading')
    {
        if (!is_array($payload)) {
            return ['status' => $fallbackStatus, 'message' => 'WA engine mengirim respons yang tidak valid'];
        }

        $status = $payload['status'] ?? $payload['state'] ?? $payload['session_status'] ?? null;
        $qr = $payload['qr'] ?? $payload['qrCode'] ?? $payload['qr_code'] ?? $payload['image'] ?? $payload['qr_url'] ?? $payload['qrUrl'] ?? null;

        if ($qr === null && isset($payload['data']) && is_array($payload['data'])) {
            $status = $status ?? ($payload['data']['status'] ?? $payload['data']['state'] ?? null);
            $qr = $payload['data']['qr'] ?? $payload['data']['qrCode'] ?? $payload['data']['qr_code'] ?? $payload['data']['image'] ?? $payload['data']['qr_url'] ?? $payload['data']['qrUrl'] ?? null;
        }

        if ($status === null && $qr) {
            $status = 'qr_ready';
        }

        return [
            'status' => $status ?? $fallbackStatus,
            'qr' => $qr,
            'message' => $payload['message'] ?? null,
            'user' => $payload['user'] ?? ($payload['data']['user'] ?? null),
        ];
    }

    public function startSession()
    {
        $sessionId = 'user_' . Auth::id();
        $waUrl = rtrim(config('services.wa_engine.url', 'https://wa.duacerita.my.id'), '/');
        $candidates = [
            ['url' => $waUrl . '/api/wa/start', 'method' => 'post'],
            ['url' => $waUrl . '/api/start', 'method' => 'post'],
            ['url' => $waUrl . '/start', 'method' => 'post'],
        ];

        foreach ($candidates as $candidate) {
            try {
                $response = $candidate['method'] === 'post'
                    ? Http::timeout(15)->asForm()->post($candidate['url'], ['session_id' => $sessionId])
                    : Http::timeout(5)->get($candidate['url']);

                if ($response->successful()) {
                    return response()->json($this->normalizeWaPayload($response->json(), 'loading'));
                }
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                continue;
            } catch (\Exception $e) {
                continue;
            }
        }

        return response()->json([
            'status' => 'loading',
            'message' => 'WA engine sedang memulai sesi, silakan coba lagi sebentar.',
        ], 200);
    }

    public function blast(Request $request, $invitation_id)
    {
        $request->validate([
            'message' => 'required|string',
            'guest_ids' => 'required|array',
        ]);

        $invitation = Invitation::where('user_id', Auth::id())->findOrFail($invitation_id);

        if ($invitation->status !== 'active') {
            return back()->with('error', 'Maaf, Anda harus mengaktifkan undangan terlebih dahulu sebelum bisa menggunakan WhatsApp Blast.');
        }

        // Get limits and quota
        $sudahDikirim = Guest::where('invitation_id', $invitation->id)->where('is_blasted', 1)->count();

        $lastPaidOrder = Order::with('package')
            ->where('invitation_id', $invitation->id)
            ->whereNotNull('package_id')
            ->where('status', 'success')
            ->latest()
            ->first();

        $packageLimit = 0;
        if ($lastPaidOrder && $lastPaidOrder->package) {
            $features = $lastPaidOrder->package->features;
            if (is_string($features)) {
                $features = json_decode($features, true);
            }
            $packageLimit = $features['logic']['blast_limit'] ?? 0;
        }

        $addon = \App\Models\InvitationAddon::where('invitation_id', $invitation->id)->first();
        $extraQuota = $addon ? (int) $addon->extra_quota : 0;

        $totalQuota = $packageLimit + $extraQuota;
        $remainingQuota = max(0, $totalQuota - $sudahDikirim);

        // Filter guest ids that are not blasted yet
        $guests = Guest::whereIn('id', $request->guest_ids)
            ->where('invitation_id', $invitation->id)
            ->where('is_blasted', 0)
            ->whereNotNull('phone_number')
            ->get();

        if ($guests->count() > $remainingQuota) {
            return back()->with('error', "Gagal melakukan blast! Jumlah tamu yang dipilih ({$guests->count()}) melebihi sisa kuota blast Anda ({$remainingQuota}). Silakan top up kuota terlebih dahulu.");
        }

        $sessionId = 'user_' . Auth::id();
        $linkUndangan = url("/{$invitation->slug}");
        $delaySeconds = 0;

        foreach ($guests as $guest) {
            // Set as blasted immediately to prevent double-sending/exceeding limits
            $guest->update(['is_blasted' => 1]);

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
        $waUrl = config('services.wa_engine.url', 'https://wa.duacerita.my.id/');

        $response = Http::post($waUrl . '/api/wa/logout', [
            'session_id' => $sessionId,
        ]);

        return response()->json($response->json());
    }

    // 🔥 FUNGSI BARU SEBAGAI JEMBATAN (PROXY) CEK STATUS 🔥
    public function checkStatus($session_id)
    {
        $waUrl = rtrim(config('services.wa_engine.url', 'https://wa.duacerita.my.id'), '/');
        $candidates = [
            "{$waUrl}/api/wa/status/{$session_id}",
            "{$waUrl}/api/status/{$session_id}",
            "{$waUrl}/status/{$session_id}",
        ];

        foreach ($candidates as $candidate) {
            try {
                $response = Http::timeout(5)->get($candidate);

                if ($response->successful()) {
                    return response()->json($this->normalizeWaPayload($response->json(), 'loading'));
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return response()->json(['status' => 'loading', 'message' => 'Status WA sedang dipantau ulang'], 200);
    }
}

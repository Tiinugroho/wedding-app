<?php

namespace App\Jobs;

use App\Models\Guest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendWaBlastJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $guest;
    public $messageTemplate;
    public $invitationLink;
    public $sessionId;

    public function __construct(Guest $guest, $messageTemplate, $invitationLink, $sessionId)
    {
        $this->guest = $guest;
        $this->messageTemplate = $messageTemplate;
        $this->invitationLink = $invitationLink;
        $this->sessionId = $sessionId;
    }

    public function handle(): void
    {
        $linkTamu = $this->invitationLink . '?to=' . urlencode($this->guest->name);
        $pesanFinal = str_replace(
            ['{nama}', '{link}'],
            [$this->guest->nama, $linkTamu],
            $this->messageTemplate
        );

        // Tembak ke Node.js local / prod server
        $waUrl = config('services.wa_engine.url', 'http://wa.duacerita.my.id/');
        $response = Http::post($waUrl . '/api/wa/send', [
            'session_id' => $this->sessionId,
            'number' => $this->guest->phone_number,
            'message' => $pesanFinal
        ]);

        if ($response->successful()) {
            // Berhasil dikirim, catat waktu blast
            $this->guest->update([
                'blasted_at' => now()
            ]);
        } else {
            // JIKA GAGAL: Kembalikan status blast agar kuota/tamu bisa di-blast ulang
            $this->guest->update([
                'is_blasted' => false
            ]);

            // Catat di Log Laravel untuk memudahkan Anda melakukan debug
            \Log::error("Gagal mengirim WA ke {$this->guest->name} ({$this->guest->phone_number}). Status Node.js: " . $response->status());
        }
    }
}
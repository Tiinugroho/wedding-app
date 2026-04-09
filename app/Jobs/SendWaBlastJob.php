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
        // Rangkai pesan: Ganti {nama} dan {link}
        $linkTamu = $this->invitationLink . '?to=' . urlencode($this->guest->name);
        $pesanFinal = str_replace(
            ['{nama}', '{link}'], 
            [$this->guest->name, $linkTamu], 
            $this->messageTemplate
        );

        // Tembak ke Node.js local (port 3000)
        $response = Http::post('http://127.0.0.1:3000/api/wa/send', [
            'session_id' => $this->sessionId,
            'number' => $this->guest->phone_number,
            'message' => $pesanFinal
        ]);

        if ($response->successful()) {
            $this->guest->update([
                'is_blasted' => true,
                'blasted_at' => now()
            ]);
        }
    }
}
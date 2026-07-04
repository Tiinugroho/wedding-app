<?php

namespace App\Jobs;

use App\Models\Guest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWaBlastJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $guest;
    protected $messageTemplate;
    protected $linkUndangan;
    protected $sessionId;

    /**
     * Create a new job instance.
     */
    public function __construct(Guest $guest, $messageTemplate, $linkUndangan, $sessionId)
    {
        $this->guest = $guest;
        $this->messageTemplate = $messageTemplate;
        $this->linkUndangan = $linkUndangan;
        $this->sessionId = $sessionId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 1. Format Pesan (Ganti placeholder jika ada)
        // Placeholder yang didukung:
        // [nama] -> Nama Tamu
        // [link] -> Link Undangan Tamu
        $message = $this->messageTemplate;
        $message = str_replace('[nama]', $this->guest->name, $message);

        // Link undangan personal (misal: https://domain.com/wedding-slug?to=Nama+Tamu)
        $personalLink = $this->linkUndangan . '?to=' . $this->guest->slug_name;
        $message = str_replace('[link]', $personalLink, $message);

        // 2. Konfigurasi Endpoint WA Gateway
        $waUrl = config('services.wa_engine.url', 'https://wa.duacerita.my.id');
        $apiKey = config('services.wa_engine.api_key');

        // 3. Kirim HTTP POST Request ke Node.js Gateway
        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'Content-Type' => 'application/json'
            ])->timeout(10)->post($waUrl . '/api/message/send/text', [
                        'sessionId' => $this->sessionId,
                        'to' => $this->guest->phone_number,
                        'message' => $message
                    ]);

            $result = $response->json();

            if ($response->successful() && isset($result['success']) && $result['success'] === true) {
                Log::info("WA Blast berhasil terkirim ke {$this->guest->name} ({$this->guest->phone_number})");
            } else {
                Log::error("Gagal kirim WA Blast ke {$this->guest->name}: " . json_encode($result));
                // Opsional: Tandai is_blasted kembali ke 0 jika gagal agar bisa di-retry
                $this->guest->update(['is_blasted' => 0]);
            }
        } catch (\Exception $e) {
            Log::error("Exception saat kirim WA Blast ke {$this->guest->name}: " . $e->getMessage());
            $this->guest->update(['is_blasted' => 0]);

            // Lempar kembali exception agar Laravel Queue merekam sebagai failed job jika diperlukan
            throw $e;
        }
    }
}
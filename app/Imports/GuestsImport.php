<?php

namespace App\Imports;

use App\Models\Guest;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GuestsImport implements ToModel, WithHeadingRow
{
    protected $invitationId;

    public function __construct($invitationId)
    {
        $this->invitationId = $invitationId;
    }

    public function model(array $row)
    {
        $nama = trim($row['nama_tamu'] ?? '');

        // 🔥 FILTER PINTAR: Abaikan baris kosong atau baris yang berisi catatan panduan
        if (
            empty($nama) || 
            str_contains($nama, '*** PANDUAN') || 
            str_contains($nama, 'JANGAN MENGUBAH') || 
            str_contains($nama, 'Sistem RuangRestu') ||
            preg_match('/^[1-4]\./', $nama) // Mengabaikan baris yang diawali "1.", "2.", dst.
        ) {
            return null;
        }

        return new Guest([
            'invitation_id' => $this->invitationId,
            'name'          => $nama,
            'phone_number'  => $this->formatPhone($row['nomor_wa'] ?? null),
            'slug_name'     => urlencode($nama),
            'is_present'    => 0,
            'is_blasted'    => 0,
        ]);
    }

    private function formatPhone($number) 
    {
        if (empty($number)) return null;

        // 1. Hapus SEMUA karakter selain angka (menghapus +, spasi, dan strip -)
        $number = preg_replace('/[^0-9]/', '', $number);

        // 2. Jika dimulai dengan angka 0 (misal: 0812...), ubah 0 jadi 62
        if (Str::startsWith($number, '0')) {
            return '62' . substr($number, 1);
        }

        // 3. Jika dimulai langsung dengan 8 (misal: 85749237...), tambahkan 62 di depannya
        if (Str::startsWith($number, '8')) {
            return '62' . $number;
        }

        // Jika sudah dimulai dengan 62 atau kode negara lain, kembalikan apa adanya
        return $number;
    }
}
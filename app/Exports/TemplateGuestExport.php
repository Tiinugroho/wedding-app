<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TemplateGuestExport implements FromArray, WithHeadings, WithColumnFormatting, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'nama_tamu',
            'nomor_wa'
        ];
    }

    public function array(): array
    {
        return [ 
            
            // --- JARAK 3 BARIS KOSONG ---
            ['', ''],
            ['', ''],
            ['', ''],

            // --- CATATAN CARA PENGISIAN ---
            // 🔥 PERBAIKAN: Ganti tanda sama dengan (=) menjadi bintang (*) agar tidak dianggap rumus
            ['*** PANDUAN PENGISIAN DAFTAR TAMU ***', ''],
            ['1. Kolom "nama_tamu" WAJIB DIISI. Jika dibiarkan kosong, tamu tidak akan diimpor.', ''],
            ['2. Kolom "nomor_wa" OPSIONAL. Kosongkan saja jika tamu tidak akan dikirimi WhatsApp Blast.', ''],
            ['3. Penulisan nomor WA sangat BEBAS (Boleh pakai +62, 08, awalan 8, spasi, atau strip).', ''],
            ['   Sistem RuangRestu akan otomatis merapikan nomor tersebut.', ''],
            ['4. JANGAN MENGUBAH judul kolom di baris paling atas (nama_tamu & nomor_wa).', '']
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT, // Mencegah huruf E+ muncul pada nomor panjang
        ];
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    use HasFactory;

    // 1. TAMBAHKAN BARIS INI UNTUK MEMAKSA NAMA TABEL
    protected $table = 'musics';

    protected $fillable = [
        'title',
        'category', // <--- Tambahkan ini
        'file_path',
    ];

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }
}
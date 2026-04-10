<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relasi balik ke tabel Invitation
    public function invitation()
    {
        return $this->belongsTo(Invitation::class);
    }
}
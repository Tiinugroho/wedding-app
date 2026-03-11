<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitationDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Kunci Dinamis: Data JSON teks pengantin, jadwal, dan maps akan menjadi Array
    protected $casts = [
        'content' => 'array',
    ];

    public function invitation()
    {
        return $this->belongsTo(Invitation::class);
    }
}
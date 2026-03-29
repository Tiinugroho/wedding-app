<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitationDetail extends Model
{
    use HasFactory;

    // WAJIB ADA AGAR DATA BISA DISIMPAN
    protected $fillable = [
        'invitation_id',
        'content',
    ];

    public function invitation()
    {
        return $this->belongsTo(Invitation::class);
    }
}
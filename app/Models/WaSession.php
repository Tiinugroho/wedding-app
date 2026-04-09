<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaSession extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'session_id', 'wa_number', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
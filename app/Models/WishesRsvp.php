<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishesRsvp extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'wishes_rsvps';

    public function invitation()
    {
        return $this->belongsTo(Invitation::class);
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }
}
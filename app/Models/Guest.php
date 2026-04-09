<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_present' => 'boolean',
    ];

    protected $fillable = ['invitation_id', 'name', 'phone_number', 'slug_name', 'is_present', 'is_blasted', 'blasted_at'];

    public function invitation()
    {
        return $this->belongsTo(Invitation::class);
    }

    public function rsvps()
    {
        return $this->hasMany(WishesRsvp::class);
    }
}

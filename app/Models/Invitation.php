<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    // Relasi ke Master Data
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function template()
    {
        return $this->belongsTo(Template::class);
    }
    public function music()
    {
        return $this->belongsTo(Music::class);
    }

    // Relasi ke Detail Konten (One-to-One)
    public function detail()
    {
        return $this->hasOne(InvitationDetail::class);
    }

    // Relasi ke Fitur Pendukung (One-to-Many)
    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }
    public function digitalGifts()
    {
        return $this->hasMany(DigitalGift::class);
    }
    public function guests()
    {
        return $this->hasMany(Guest::class);
    }
    public function rsvps()
    {
        return $this->hasMany(WishesRsvp::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

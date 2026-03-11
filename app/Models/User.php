<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = ['id'];

    protected $hidden = ['password', 'remember_token'];
protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number', // Pastikan ini ada
        'google_id',    // Pastikan ini ada
        'avatar',       // Pastikan ini ada
        'role',         // Pastikan ini ada
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

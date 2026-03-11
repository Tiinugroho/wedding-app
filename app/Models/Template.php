<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Kunci Dinamis: Otomatis ubah JSON di database menjadi Array saat dipanggil
    protected $casts = [
        'required_fields' => 'array',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }
}
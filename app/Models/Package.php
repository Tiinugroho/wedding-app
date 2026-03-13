<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    // 1. WAJIB ADA: Mendaftarkan kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'name',
        'price',
        'original_price',
        'description',
        'features',
        'is_active',
    ];

    // 2. WAJIB ADA: Mengubah data Array dari form menjadi JSON secara otomatis ke database
    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
    ];
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
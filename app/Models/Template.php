<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'package_id', // Ganti price menjadi package_id
        'name',
        'view_path',
        'thumbnail',
        'required_fields',
        'is_active',
    ];

    // Konversi otomatis ke format JSON / Array dan Boolean
    protected $casts = [
        'required_fields' => 'array',
        'is_active' => 'boolean',
    ];

    // Relasi: Template dimiliki oleh satu Kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi: Template dikelompokkan ke dalam satu Paket (Harga & Fitur)
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
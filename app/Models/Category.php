<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    // Relasi: Satu Kategori memiliki banyak Template
    public function templates()
    {
        return $this->hasMany(Template::class);
    }
}
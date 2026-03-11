<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'musics';

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }
}
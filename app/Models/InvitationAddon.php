<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvitationAddon extends Model
{
    // Pastikan di App\Models\Order ada ini:
    protected $fillable = ['order_number', 'user_id', 'invitation_id', 'package_id', 'amount', 'status', 'reference'];

    public function invitation()
    {
        return $this->belongsTo(Invitation::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_number', 'order_number');
    }

    // 🔥 FUNGSI BARU UNTUK CEK STATUS ADDON 🔥
    public function getStatusAttribute($value)
    {
        return $value;
    }
}

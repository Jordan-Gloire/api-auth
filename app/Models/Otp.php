<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    //
    protected $filable = [
        'code',
        'user_id',
        'expires_at',
        'is_used',
    ];

   public function user()
    {
        return $this->belongsTo(User::class);
    }
}
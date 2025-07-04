<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Split extends Model
{
    use HasFactory;

    public function recebedor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user_services()
    {
        return $this->hasMany(UserServices::class, 'payment_id');
    }
    
}

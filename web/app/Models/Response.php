<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
        {
            return $this->belongsTo(User::class, 'user_id');
        }
    public function lending()
        {
            return $this->belongsTo(Lending::class);
        }

}

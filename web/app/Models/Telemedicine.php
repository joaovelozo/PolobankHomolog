<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telemedicine extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function telemedicines()
    {
        return $this->belongsTo(TelemedPlan::class);
    }
}

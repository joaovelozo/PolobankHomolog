<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lending extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }   

public function responses()
{
    return $this->hasMany(Response::class);
}

public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }

    
}

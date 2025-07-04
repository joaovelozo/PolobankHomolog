<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreateAccount extends Model
{
    protected $table = 'create_accounts';
    protected $fillable = [ 'session_id', 'step', 'data'];

    protected $casts = [
        'data' => 'array',
    ];
}

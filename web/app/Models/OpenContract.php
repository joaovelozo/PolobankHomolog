<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenContract extends Model
{
    use HasFactory;
    protected $guarded = [];
     // Se um contrato aberto pertence a um único usuário
     public function user()
     {
         return $this->belongsTo(User::class);
     }
}

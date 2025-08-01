<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;
    protected $guarded = [];

    //RelationShip transaction
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}

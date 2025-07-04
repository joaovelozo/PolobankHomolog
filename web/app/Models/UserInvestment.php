<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInvestment extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable = ['user_id', 'investment_id', 'type_id', 'amount','start_date','end_date','calculated_return'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function investment()
    {
        return $this->belongsTo(Investment::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }


}

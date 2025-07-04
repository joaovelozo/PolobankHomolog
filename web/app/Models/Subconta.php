<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subconta extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'cpfCnpj',
        'birthDate',
        'companyType',
        'phone',
        'mobilePhone',
        'address',
        'addressNumber',
        'complement',
        'province',
        'postalCode',
    ];
}

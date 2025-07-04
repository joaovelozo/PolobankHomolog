<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Type;

class PixController extends Controller
{

    public function getTypes()
    {
        $types = Type::all()->pluck('name');
        return response()->json($types);
    }
}

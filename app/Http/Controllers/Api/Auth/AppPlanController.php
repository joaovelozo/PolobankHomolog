<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class AppPlanController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        if($plans->isEmpty()){
            return response()->json(['message' => 'Nenhum plano encontrado'], 404);
        }
        return response()->json($plans, 200);
    }
}

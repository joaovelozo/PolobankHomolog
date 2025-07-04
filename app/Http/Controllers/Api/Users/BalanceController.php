<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;


class BalanceController extends Controller
{

        public function balance(Request $request)
        {
            // Recupera o usuário autenticado
            $user = Auth::user();

            // Caso não tenha um usuário autenticado
            if (!$user) {
                return response()->json([
                    'error' => 'Usuário não autenticado.'
                ], 401);
            }

            // Retorna o token de acesso e o saldo do usuário
            return response()->json([
                'access_token' => $user->createToken($user->name . '-AuthToken')->plainTextToken,
                'balance' => $user->balance(),
            ]);
        }
    }

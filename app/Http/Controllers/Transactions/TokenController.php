<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class TokenController extends Controller
{
    public function showToken()
    {
        // Gera um token aleatório e salva na sessão
        $token = Str::random(8);
        Session::put('generated_token', $token);

        return view('users.transactions.token', ['token' => $token]);
    }

    // Valida o token enviado pelo usuário
    public function validateToken(Request $request)
{
    $request->validate([
        'user_token' => 'required',
    ]);

    $generatedToken = Session::get('generated_token');

    if ($generatedToken === $request->input('user_token')) {
        // Retorna JSON em vez de redirecionar
        return response()->json([
            'success' => true,
            'message' => 'Token validado com sucesso!'
        ]);
    }

    // Retorna JSON para erro
    return response()->json([
        'success' => false,
        'message' => 'Token inválido.'
    ], 422);
}
}

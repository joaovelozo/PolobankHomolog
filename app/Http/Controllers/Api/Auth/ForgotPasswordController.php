<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    /**
     * Envia o e-mail de redefinição de senha.
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Valida o e-mail fornecido
        $request->validate(['email' => 'required|email']);

        // Tenta enviar o link de redefinição de senha
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Verifica se o envio foi bem-sucedido ou não
        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Um link de redefinição de senha foi enviado para o seu e-mail.'
            ], 200);
        }

        // Se falhar, retorna um erro
        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}

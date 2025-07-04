<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Agency;

class CheckController extends Controller
{
    public function validateEmail($email)
    {
        // Busca o usuário pelo e-mail
        $user = User::where('email', $email)->first();

        // Verifica se o usuário existe
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        // Busca a agência associada ao usuário, se existir
        $agency = $user->agency; // Certifique-se de que o relacionamento está definido no modelo User

        // Se não existir agência, crie uma nova instância para fins de resposta
        if (!empty($agency)) {
            $agency = Agency::find(1);
        }

        // Retorna os dados do usuário e da agência como resposta JSON
        return response()->json([
            'user' => $user,
            'agency' => $agency
        ]);
    }

    public function checkEmail(Request $request)
    {
        // Validação do e-mail
        $request->validate([
            'email' => 'required|email',
        ]);

        // Busca o usuário pelo e-mail
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Se o usuário existir, retorna uma resposta JSON com os dados do usuário
            return response()->json([
                'message' => 'Usuário encontrado',
                'user' => $user
            ]);
        } else {
            // Se o usuário não existir, retorna uma mensagem indicando a necessidade de registro
            return response()->json([
                'message' => 'Usuário não encontrado, redirecionar para registro',
                'email' => $request->email
            ], 404);
        }
    }
}

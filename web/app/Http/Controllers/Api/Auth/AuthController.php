<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Models\Agency;
use App\Models\Card;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Models\OpenContract;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Str;
use Illuminate\Support\Facades\DB;
use Image;
use App\Models\Split;
use App\Models\UserServices;
use App\Services\TelemedicinaService;
use App\Models\Plan;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{

    //Login  API
    public function login(Request $request)
    {
        $loginUserData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|min:8'
        ]);

        // Busca o usuário pelo e-mail
        $user = User::where('email', $loginUserData['email'])->first();

        // Verifica se o usuário existe e a senha está correta
        if (!$user || !Hash::check($loginUserData['password'], $user->password)) {
            return response()->json([
                'message' => 'Email ou Senha Não Conferem!'
            ], 401);
        }

        // Gera e atribui o token de acesso ao usuário
        $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;
        $user->access_token = $token;
        $user->save();

        return response()->json([
            'access_token' => $token,
            'name' => $user->name,
            'account' => $user->account,
            'agency_id' => $user->agency_id,
            'balance' => $user->balance,
        ]);
    }

    //Recuperdados do usuário
    public function userData(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json(['error' => 'Usuário não encontrado'], 404);
            }

            $agencyNumber = null;
            $agency = null;

            if ($user->agency_id) {
                $agency = Agency::find($user->agency_id);
            }

            if ($agency) {
                $agencyNumber = $agency->number;
            } else {
                $agencyNumber = ""; // Definir um valor padrão (opcional)
            }

            return response()->json([
                'name' => $user->name,
                'account' => $user->account,
                'pix_key' => $user->pix_key,
                'agency_number' => $agencyNumber, // Incluindo o número real da agência
                'balance' => $user->balance
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao recuperar os dados do usuário'], 500);
        }
    }
    //Logout API
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json([
            "message" => "Até Logo!"
        ]);
    }

    public function status(Request $request)
    {
        // Obtém o usuário autenticado
        $user = auth()->user();

        // Verifica se o usuário está autenticado
        if ($user) {
            return response()->json([
                'status' => 'success',
                'data' => [

                    'status' => $user->status, // Adiciona o status do usuário
                ]
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Usuário não autenticado.'
            ], 401);
        }
    }
}

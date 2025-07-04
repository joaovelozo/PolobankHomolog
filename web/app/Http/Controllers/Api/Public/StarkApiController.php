<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class StarkApiController extends Controller
{
    public function Send()
    {
        // Buscar todos os usuários com status 'active'
        $users = User::select('name', 'document','created_at','status')
                     ->where('status', 'active')
                     ->get();

        // Verificar se há usuários encontrados
        if ($users->isEmpty()) {
            return response()->json(['message' => 'Nenhum usuário encontrado'], 404);
        }

        // Retornar os dados como JSON
        return response()->json($users, 200);
    }
}

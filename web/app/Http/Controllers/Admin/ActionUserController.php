<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserAction;
use Illuminate\Http\Request;
use Auth;

class ActionUserController extends Controller
{
    public function index()
    {
        $act = UserAction::all();
        return view('admin.actions.index',compact('act'));
    }

    public function saveAction(Request $request)
    {
        // Validar os dados recebidos
        $request->validate([
            'action' => 'required|string',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric'
        ]);

        // Criar a ação no banco de dados
        UserAction::create([
            'user_id' => Auth::id(),  // O ID do usuário autenticado
            'action' => $request->action,  // Ação executada
            'description' => $request->description,  // Descrição (opcional)
            'latitude' => $request->latitude,  // Latitude (opcional)
            'longitude' => $request->longitude,  // Longitude (opcional)
            'ip_address' => $request->ip()  // Captura o IP do usuário
        ]);

        // Retornar resposta JSON
        return response()->json([
            'message' => 'Ação registrada com sucesso!',
            'status' => 'success'
        ], 200);
    }
}


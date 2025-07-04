<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Comunication;
use Illuminate\Http\Request;
use Auth;

class ComunicationController extends Controller
{
    // Recupera todas as mensagens (somente leitura)
    public function index()
    {
        $messages = Comunication::all(); // Recupera todas as mensagens

        if ($messages->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Nenhuma mensagem encontrada.',
            ], 404);
        }

        // Excluindo campos extras se necessário e retornando os campos específicos
        $messagesData = $messages->map(function ($message) {
            return [
                'id' => $message->id,
                'title' => $message->title,
                'description' => $message->description,
                'url' => $message->url,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $messagesData,
        ], 200);
    }

    // Exibe uma mensagem específica
    public function show($id)
    {
        $message = Comunication::find($id);

        if (!$message) {
            return response()->json([
                'success' => false,
                'message' => 'Mensagem não encontrada.',
            ], 404);
        }

        // Retorna os campos específicos da mensagem
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $message->id,
                'title' => $message->title,
                'description' => $message->description,
                'url' => $message->url,
            ],
        ], 200);
    }
}

<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\UserServices;
use Illuminate\Http\Request;

class TelemedicalController extends Controller
{
    public function Send()
    {
        // Buscar os serviços do usuário com status 'active', incluindo o relacionamento com User e Payment
        $userServices =  UserServices::with(['user', 'payment'])
        ->whereHas('payment', function ($query) {
            $query->where('title', 'Telemedicina');
        })
        ->whereHas('user', function ($query) {
            $query->where('status', 'active');
        })
        ->where('status', 'active') // Filtrar apenas serviços com status 'active'
        ->distinct('user_id') // Garantir usuários únicos com base no user_id diretamente no banco de dados
        ->get();

        // Estruturar os dados para a resposta
        $response = $userServices->map(function ($userService) {
            return [
                'user_id' => $userService->user->id,
                'user_name' => $userService->user->name,
                'user_document' => $userService->user->document,
                'payment_amount' => $userService->payment->amount ?? null,
                'payment_date' => $userService->created_at ?? null,
                'status' => $userService->status,
            ];
        });

        return response()->json($response);
    }
}

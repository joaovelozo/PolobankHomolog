<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contract;
use Auth;

class ContractController extends Controller
{
    public function getAllContracts()
{
    // Recupera todos os contratos
    $contracts = Contract::all();

    // Verifica se há contratos
    if ($contracts->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'Nenhum contrato encontrado.',
        ], 404);
    }

    // Retorna os contratos no formato JSON
    return response()->json([
        'success' => true,
        'data' => $contracts,
    ], 200);
}
    public function getOpenContract()
{
    // Recupera o usuário logado
    $user = Auth::user();

    // Verifica se o usuário possui um contrato associado
    if ($user->openContract) {
        // Se o usuário tiver um contrato associado, recupere-o
        $contract = $user->openContract;

        return response()->json([
            'success' => true,
            'data' => $contract,
        ], 200);
    } else {
        // Se não houver contrato associado, retorne um erro
        return response()->json([
            'success' => false,
            'message' => 'Você não possui um contrato associado.',
        ], 404);
    }
}
}

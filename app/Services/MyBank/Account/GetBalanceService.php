<?php
namespace App\Services\MyBank\Account;

use App\Models\User;
use App\Services\MyBank\BalanceService;
use Illuminate\Support\Facades\Auth;


class GetBalanceService
{
    protected $balanceService;

    public function __construct(BalanceService $balanceService)
    {
        $this->balanceService = $balanceService;
    }

    public function GetRealBalance(array $getBalance, $userId = null): array
    {
        $response = $this->balanceService->RealBalance($getBalance);

        if (isset($response['error']) && $response['error'] === false) {

            $balanceAvailable = $response['balanceAvailable'] ?? 0;

            // Atualiza o saldo no banco
            $this->updateUserBalance($balanceAvailable, $userId);

            return [
                'status' => 'success',
                'balanceAvailable' => $balanceAvailable,
                'toReceive' => $response['balanceToReceive'] ?? 0,
                'currency' => $response['currencyCode'] ?? 'BRL',
                'symbol' => $response['symbol'] ?? 'R$',
                'message' => $response['returnMessage'] ?? 'Sucesso',
            ];
        }

        return [
            'status' => 'error',
            'code' => $response['returnCode'] ?? '??',
            'message' => $response['returnMessage'] ?? 'Erro ao obter saldo',
        ];
    }

    protected function updateUserBalance($balance, $userId = null)
    {
        // Se não for passado um ID, pega o usuário logado
        $user = $userId ? User::find($userId) : Auth::user();

        if ($user) {
            $user->balance = $balance;
            $user->save();
        }
    }
}

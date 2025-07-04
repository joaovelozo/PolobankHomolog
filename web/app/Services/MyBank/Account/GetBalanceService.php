<?php
namespace App\Services\MyBank\Account;

use App\Services\MyBank\BalanceService;


class GetBalanceService
{
    protected $balanceService;

    public function __construct(BalanceService $balanceService)
    {
        $this->balanceService = $balanceService;
    }

    public function GetRealBalance(array $getBalance): array
{
    $response = $this->balanceService->RealBalance($getBalance);

    if (isset($response['error']) && $response['error'] === false) {
        return [
            'status' => 'success',
            'balanceAvailable' => $response['balanceAvailable'] ?? 0,
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
}

<?php
namespace App\Services\MyBank\Payment\Internal;

use App\Services\MyBank\cprService;
use App\Services\MyBank\InternalService;

class InternalTransferService
{
    protected $internalService;


    public function __construct(InternalService $internalService)
    {
        $this->internalService = $internalService;
    }

    /**
     * Realiza uma transferência interna junto ao serviço externo.
     */
    public function inTransfer(array $params)
    {

        // Monta o payload para envio
        $payload = [
           'transfer' => [
            'accountNumber' =>  $params['accountNumber'],
            'documentNumber' => $params['documentNumber'],
             'amount' => $params['amount'],

           ]

        ];
        // Chama o serviço externo para processar a transferência
        return $this->internalService->internalTransfer($payload);
    }
}

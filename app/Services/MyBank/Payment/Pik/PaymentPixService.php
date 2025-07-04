<?php

namespace App\Services\MyBank\Payment\Pik;

use App\Services\MyBank\cprService;
use App\Services\MyBank\PixService;
use App\Services\MyBank\TokenService;

class PaymentPixService
{
    protected $pixService;
    protected $cprService;
    protected $tokenService;

    public function __construct(PixService $pixService,TokenService $tokenService)
    {
        $this->pixService = $pixService;
        $this->tokenService = $tokenService;
    }

    /**
     * Gera um pagamento Pix sem assinatura digital (CreatePaymentPix).
     */
    public function PaymentGeneratePix(array $payload)
    {
        return $this->pixService->CreatePaymentPix($payload);
    }

    /**
     * Realiza um pagamento Pix com assinatura digital.
     */
    public function PixPayment(array $params)
    {
        \Log::info('Entrou em PixPayment', $params);

        $payload = [
            'customId' => $params['customId'],
            'amount' => $params['amount'],
            'favorecido' => [
                'nome' => $params['nome'],
                'cpfcnpj' => $params['cpfcnpj'],
                'chave' => [
                    'idTipoChavePIX' => $params['idTipoChavePIX'],
                    'chavePIX' => $params['chavePIX'],
                ],
            ],
            'confirmationUrl' => $params['confirmationUrl'],
            'updateUrl' => $params['updateUrl'],
        ];

        \Log::info('Payload enviado para pixPay', $payload);

        try {
            $response = $this->pixService->pixPay($payload);
            \Log::info('Resposta recebida de pixPay', ['response' => $response]);
            return $response;
        } catch (\Throwable $e) {
            \Log::error('Erro no pixPay: ' . $e->getMessage());
            throw $e; // propaga para o controller capturar
        }
    }
}

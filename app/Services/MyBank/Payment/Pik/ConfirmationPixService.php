<?php

namespace App\Services\MyBank\Payment\Pik;


use App\Services\MyBank\PixService;


class ConfirmationPixService
{
    protected $pixService;


    public function __construct(PixService $pixService)
    {
        $this->pixService = $pixService;
    }
    public function confirmationPix(array $params)
    { {
            \Log::info('Entrou em PixPayment', $params);

            $response = $this->pixService->confirmationPix($params);

            return $response;
        }
    }
}

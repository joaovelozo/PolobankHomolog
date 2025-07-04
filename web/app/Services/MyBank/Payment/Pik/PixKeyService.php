<?php

namespace App\Services\MyBank\Payment\Pik;

use App\Services\MyBank\PixService;

class PixKeyService
{
    protected $pixService;

    public function __construct(PixService $pixService)
    {
        $this->pixService = $pixService;
    }

    public function createPixKey(array $pixData)
    {
        $payload = [
            "type" => $pixData['type']
        ];

        // Apenas se o tipo NÃO for EVP é que acrescentamos o key
        if ($pixData['type'] !== 'EVP') {
            $payload['key'] = $pixData['key'];
        }

        return $this->pixService->CreateKey($payload);
    }
}

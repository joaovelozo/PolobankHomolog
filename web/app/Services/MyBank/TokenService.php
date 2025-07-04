<?php

namespace App\Services\MyBank;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class TokenService
{
    public function generateToken(string $baseUrl, string $clientId, string $clientSecret, string $grantType = 'client_credentials')
    {
        $url = rtrim($baseUrl, '/') . '/token';

        $postData = [
            'clientid' => $clientId,
            'clientsecret' => $clientSecret,
            'grant_type' => $grantType,
        ];

        try {
            Log::info('Enviando POST para /token com dados:', $postData);

            $response = Http::asForm()->post($url, $postData);

            $body = $response->json();
            Log::info('Resposta do token:', $body);

            if ($response->successful() && isset($body['access_token'])) {
                return $body;
            }

            throw new Exception('Resposta inválida ao gerar token: ' . json_encode($body));
        } catch (Exception $e) {
            Log::error('Erro ao gerar token: ' . $e->getMessage());
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }
}

<?php

namespace App\Services\MyBank;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Auth;

class TokenService
{
    public function generateToken(string $baseUrl)
    {
        $url = rtrim($baseUrl, '/') . '/token';



        $postData = [
            'clientid' => config('mb.client_id'),
            'clientsecret' => config('mb.client_secret'),
            'grant_type' => config('mb.grant_type'),
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

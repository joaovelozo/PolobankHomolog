<?php

namespace App\Services\Mybank;

use App\Services\MyBank\TokenService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MBService
{
    protected $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function createCustomer(array $payload)
    {
        $baseUrl = rtrim(config('mb.url'), '/');
        $clientId = config('mb.client_id');
        $clientSecret = config('mb.client_secret');
        $grantType = config('mb.grant_type');
        $applicationToken = config('mb.application_token');

        // Gera o token via TokenService
        $tokenResponse = $this->tokenService->generateToken($baseUrl, $clientId, $clientSecret, $grantType);

        if (!isset($tokenResponse['access_token'])) {
            throw new \Exception('Token de acesso não encontrado na resposta');
        }

        $token = $tokenResponse['access_token'];

        // Monta a URL CORreta
        $url = $baseUrl . '/CreateCustomer';
        Log::info('URL que será usada (CreateCustomer): ' . $url);

        $method = !empty($payload) ? 'POST' : 'GET';
        Log::info('Método da requisição: ' . $method);
        Log::info('URL que será usada (CreateBusiness): ' . $url);

        // debug opcional:
        Log::info('Payload que será enviado!', ['payload' => $payload]);

        // Envia o POST com o token + application token
        $response = Http::withToken($token)
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'ApplicationToken' => $applicationToken,
            ])
            ->post($url, $payload);

        Log::info('Resposta da API!', ['resposta' => $response->json()]);

        return $response->json();
    }

    public function createBusiness(array $payload)
    {
        $baseUrl = rtrim(config('mb.url'), '/');
        $clientId = config('mb.client_id');
        $clientSecret = config('mb.client_secret');
        $grantType = config('mb.grant_type');
        $applicationToken = config('mb.application_token');

        // Gera o token via TokenService
        $tokenResponse = $this->tokenService->generateToken($baseUrl, $clientId, $clientSecret, $grantType);

        if (!isset($tokenResponse['access_token'])) {
            throw new \Exception('Token de acesso não encontrado na resposta');
        }

        $token = $tokenResponse['access_token'];

        // Monta a URL CORreta
        $url = $baseUrl . '/Customer/CreateCustomer';
        Log::info('Rota que será usada: Customer/CreateCustomer'); // <- atualizado

        // Verifica se é um GET ou um POST automaticamente
        $method = !empty($payload) ? 'POST' : 'GET';
        Log::info('Método da requisição: ' . $method);
        Log::info('URL que será usada (CreateBusiness): ' . $url);

        // debug opcional:
        Log::info('Payload que será enviado!', ['payload' => $payload]);

        // Realiza a requisição
        if ($method == 'POST') {
            $response = Http::withToken($token)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'ApplicationToken' => $applicationToken,
                ])
                ->post($url, $payload);
        } else {
            $response = Http::withToken($token)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'ApplicationToken' => $applicationToken,
                ])
                ->get($url);
        }

        Log::info('Resposta da API!', ['resposta' => $response->json()]);

        return $response->json();
    }
}

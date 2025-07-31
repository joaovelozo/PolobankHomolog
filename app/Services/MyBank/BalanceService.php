<?php

namespace App\Services\MyBank;


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Auth;

class BalanceService
{
    protected $tokenService;
    protected $cprService;

    public function __construct(TokenService $tokenService, CprService $cprService)
    {
        $this->tokenService = $tokenService;
        $this->cprService = $cprService;
    }

    public function RealBalance(array $payload)
    {
        $user = Auth::user();


        $baseUrl = rtrim(config('mb.url'), '/');
        $clientId = $user->client_id;
        $clientSecret = $user->client_secret;

        $grantType = config('mb.grant_type');
        $applicationToken = config('mb.application_token');

        try {
            $tokenResponse = $this->tokenService->generateToken($baseUrl, $clientId, $clientSecret, $grantType);

            if (!isset($tokenResponse['access_token'])) {
                throw new \Exception('Token de acesso não encontrado na resposta');
            }

            $token = $tokenResponse['access_token'];
            $url = $baseUrl . '/Financial/GetRealBalance';

            Log::info('URL usada: ' . $url);

            $digitalSignature = $this->cprService->generateSignature($token);
            Log::info('Assinatura gerada: ' . $digitalSignature);

            $payload['digitalSignature'] = $digitalSignature;

            $headers = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'ApplicationToken' => $applicationToken,
                'digitalSignature' => $digitalSignature,
                'Authorization' => 'Bearer ' . $token,
            ];

            $response = Http::withHeaders($headers)->get($url, $payload);

            $statusCode = $response->status();
            $bodyRaw = $response->body();

            Log::info("HTTP status: $statusCode");
            Log::info("Resposta bruta da API: " . $bodyRaw);

            $body = $response->json();

            if (is_array($body)) {
                Log::info('Resposta da API decodificada JSON:', ['resposta' => $body]);
                return $body;
            }

            return [
                'error' => true,
                'returnMessage' => "Resposta inválida da API (não é JSON) - HTTP status: $statusCode",
                'body' => $bodyRaw,
            ];

        } catch (Exception $e) {
            Log::error('Erro ao consultar saldo: ' . $e->getMessage());

            return [
                'error' => true,
                'returnMessage' => $e->getMessage(),
            ];
        }
    }
}

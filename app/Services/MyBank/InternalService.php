<?php
namespace App\Services\MyBank;


use App\Services\MyBank\TokenService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InternalService
{
    protected $tokenService;
    protected $cprService;

    public function __construct(TokenService $tokenService, cprService $cprService)
    {
        $this->tokenService = $tokenService;
        $this->cprService = $cprService;
    }

    public function internalTransfer(array $payload)
    {
 {
        $user = Auth::user();
        $baseUrl = rtrim(config('mb.url'), '/'); // removendo o slash final se ele existe
        $clientId = $user->client_id;
        $clientSecret = $user->client_secret;
        $grantType = config('mb.grant_type');
        $applicationToken = config('mb.application_token'); // <-- pega o token do .env

        // Gera o token via TokenService
        $tokenResponse = $this->tokenService->generateToken($baseUrl, $clientId, $clientSecret, $grantType);

        if (!isset($tokenResponse['access_token'])) {
            throw new \Exception('Token de acesso não encontrado na resposta');
        }

        $token = $tokenResponse['access_token'];

        // Monta a URL CORreta
        $url = $baseUrl . '/transfer/realbalance';
        Log::info('URL que será usada (realbalance): ' . $url);

       $method = !empty($payload) ? 'POST' : 'GET';
        Log::info('Método da requisição: ' . $method);
        Log::info('URL que será usada (transfer/realbalance): ' . $url);

        // debug opcional:
        Log::info('Payload que será enviado!', ['payload' => $payload]);
         $digitalSignature = $this->cprService->generateSignature($token);

                    \Log::info('Assinatura gerada: ' . $digitalSignature);

                    $payload['digitalSignature']=  $digitalSignature;

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
}
}


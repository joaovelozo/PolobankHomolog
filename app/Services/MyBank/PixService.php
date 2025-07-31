<?php

namespace App\Services\MyBank;

use App\Services\MyBank\TokenService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\MyBank\CprService;
use Illuminate\Support\Facades\Auth;

class PixService
{
    protected $tokenService;
    protected $cprService;

    public function __construct(TokenService $tokenService, cprService $cprService,)
    {
        $this->tokenService = $tokenService;
        $this->cprService = $cprService;
    }


    public function CreateKey(array $payload)
    {
        $user = Auth::user();
        $baseUrl = rtrim(config('mb.url'), '/');
        $clientId = $user->client_id;
        $clientSecret = $user->client_secret;
        $grantType = config('mb.grant_type');
        $applicationToken = config('mb.application_token');

        $tokenResponse = $this->tokenService->generateToken($baseUrl, $clientId, $clientSecret, $grantType);

        if (!isset($tokenResponse['access_token'])) {
            throw new \Exception('Token de acesso não encontrado na resposta');
        }

        $token = $tokenResponse['access_token'];
        $url = $baseUrl . '/pix/key';
        $method = !empty($payload) ? 'POST' : 'GET';

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'ApplicationToken' => $applicationToken,
        ];

        if ($method == 'POST') {
            $response = Http::withToken($token)->withHeaders($headers)->post($url, $payload);
        } else {
            $response = Http::withToken($token)->withHeaders($headers)->get($url);
        }

        $data = $response->json();
        Log::info('Resposta da API!', ['resposta' => $data]);

        // Faz o mapeamento seguro
        return [
            'status' => $response->status(),
            'chave' => $data['key'] ?? ($data['chave'] ?? null), // tente pegar 'key' ou 'chave', depende do retorno real da API
            'tipo_chave' => $payload['type'],
            'message' => $data['message'] ?? 'Chave criada com sucesso.'
        ];
    }

    // Create Payment Pix
    public function CreatePaymentPix(array $payload)
    { {
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
            $url = $baseUrl . '/payment/pix';
            Log::info('Rota que será usada: payment/pix'); // <- atualizado

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

            //dd($response->json());
            return $response->json();
        }
    }

    //Confirmation Pix Payment
    public function confirmationPix(array $payload)
    {
        $user = Auth::user();
        $baseUrl = rtrim(config('mb.url'), '/');
        $clientId = $user->client_id;
        $clientSecret = $user->client_secret;

        $grantType = config('mb.grant_type');
        $applicationToken = config('mb.application_token');

        $tokenResponse = $this->tokenService->generateToken($baseUrl, $clientId, $clientSecret, $grantType);

        if (!isset($tokenResponse['access_token'])) {
            throw new \Exception('Token de acesso não encontrado na resposta');
        }

        $token = $tokenResponse['access_token'];

        $url = $baseUrl . '/transfer/initialize-pix-payment';
        Log::info('Rota que será usada: ' . $url);

        $method = !empty($payload) ? 'POST' : 'GET';
        Log::info('Método da requisição: ' . $method);

        $digitalSignature = $this->cprService->generateSignature($token);
        Log::info('Assinatura gerada: ' . $digitalSignature);

        $payload['digitalSignature'] = $digitalSignature;

        Log::info('Payload final que será enviado!', ['payload' => $payload]);

        $response = Http::withToken($token)
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'ApplicationToken' => $applicationToken,
            ])
            ->post($url, $payload);

        Log::info('Corpo bruto da resposta', ['body' => $response->body()]);
        Log::info('Status HTTP', ['status' => $response->status()]);

        $data = $response->json();
        Log::info('Resposta da API decodificada', ['resposta' => $data]);

        return $data;
    }


    // Create Payment Pix With
    public function pixPay(array $payload)
    { {
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
            $url = $baseUrl . '/transfer/pix';
            Log::info('Rota que será usada:Transfer Pix'); // <- atualizado


            // debug opcional:
            Log::info('Payload que será enviado!', ['payload' => $payload]);

            $digitalSignature = $this->cprService->generateSignature($token);
            \Log::info('Assinatura gerada: ' . $digitalSignature);

            $payload['digitalSignature'] =  $digitalSignature;

            // Realiza a requisição

            $response = Http::withToken($token)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'ApplicationToken' => $applicationToken,
                ])
                ->post($url, $payload);



            Log::info('Resposta da API!', ['resposta' => $response->json()]);

            //dd($response->json());
            return $response->json();
        }
    }
}

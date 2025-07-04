<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImageComparisonService
{
    protected $apiBaseUrl;

    public function __construct()
    {
        // Defina a URL base da API Python
        $this->apiBaseUrl = 'http://127.0.0.1:5000';
    }

    public function sendImagesForComparison($documentPath, $selfiePath)
    {
        // Envia a solicitação para o endpoint Python
        $response = Http::attach('document', file_get_contents($documentPath), basename($documentPath))
                        ->attach('selfie', file_get_contents($selfiePath), basename($selfiePath))
                        ->post("{$this->apiBaseUrl}/compare");

        if ($response->successful()) {
            return $response->json()['task_id'];
        } else {
            Log::error('Erro ao enviar a solicitação para o Python: ' . $response->body());
            return null;
        }
    }

    public function getComparisonStatus($taskId)
    {
        // Configura o tempo de espera máximo por tentativa (ex.: 30 segundos)
        $timeoutPerAttempt = 30;

        // Número máximo de tentativas de verificação
        $maxAttempts = 5;

        // Intervalo entre tentativas (em segundos)
        $intervalBetweenAttempts = 5;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                // Faz a requisição para obter o status com timeout
                $response = Http::timeout($timeoutPerAttempt)->get("{$this->apiBaseUrl}/status/{$taskId}");

                // Verifica se a resposta foi bem-sucedida
                if ($response->successful()) {
                    return $response->json();
                } else {
                    Log::error("Erro ao verificar o status da tarefa (tentativa {$attempt}): " . $response->body());
                }
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                // Log do erro de conexão, incluindo a tentativa atual
                Log::error("Tentativa {$attempt} falhou com erro de timeout: " . $e->getMessage());
            }

            // Espera um tempo antes de tentar novamente
            sleep($intervalBetweenAttempts);
        }

        // Retorna null se todas as tentativas falharem
        Log::error("Não foi possível obter o status da tarefa após {$maxAttempts} tentativas.");
        return null;
    }

    public function checkTaskUntilCompleted($taskId, $attempts = 10, $delay = 3)
    {
        while ($attempts > 0) {
            sleep($delay);

            $status = $this->getComparisonStatus($taskId);

            if ($status && $status['status'] === 'Processamento concluído') {
                return $status['result'];
            }

            $attempts--;
        }

        return 'A tarefa ainda está em processamento.';
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ValidStormService
{
    protected $apiBaseUrl;

    public function __construct()
    {
        // Define a URL base da API Python
        $this->apiBaseUrl = 'http://127.0.0.1:5000';
    }

    public function sendImagesForComparison($documentPath, $selfiePath)
    {
        try {
            // Envia a solicitação para o endpoint Python com timeout
            $response = Http::timeout(60) // Timeout de 60 segundos
                ->attach('document', file_get_contents($documentPath), basename($documentPath))
                ->attach('selfie', file_get_contents($selfiePath), basename($selfiePath))
                ->post("{$this->apiBaseUrl}/compare");

            if ($response->successful()) {
                return $response->json()['task_id'];
            } else {
                Log::error('Erro ao enviar a solicitação para o Python: ' . $response->body());
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Erro ao enviar as imagens para o Python: ' . $e->getMessage());
            return null;
        }
    }

    public function getComparisonStatus($taskId)
    {
        try {
            // Consulta o status da tarefa na API Python com timeout
            $response = Http::timeout(5900) // Timeout de 30 segundos
                ->get("{$this->apiBaseUrl}/status/{$taskId}");

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Erro ao verificar o status da tarefa: ' . $response->body());
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Erro ao verificar o status da tarefa: ' . $e->getMessage());
            return null;
        }
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

<?php

namespace App\Jobs;

use App\Services\ValidStormService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckComparisonStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $taskId;
    protected $attempts;

    public function __construct($taskId, $attempts = 10)
    {
        $this->taskId = $taskId;
        $this->attempts = $attempts;
    }

    public function handle(ValidStormService $validStormService)
    {
        if ($this->attempts > 0) {
            $status = $validStormService->getComparisonStatus($this->taskId);

            if ($status && $status['status'] === 'Processamento concluído') {
                // Processamento concluído - pode salvar o resultado no banco de dados ou notificar o usuário
            } else {
                // Reenviar o job para verificar novamente
                CheckComparisonStatus::dispatch($this->taskId, $this->attempts - 1)->delay(now()->addSeconds(10));
            }
        }
    }
}

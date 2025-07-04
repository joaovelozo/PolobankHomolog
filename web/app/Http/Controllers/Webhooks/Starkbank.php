<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Split;
use App\Models\Type;
use App\Models\UserServices;
use App\Services\TelemedicinaService;

class Starkbank extends Controller
{
    public function handle(Request $request)
    {
        // Decodifica o JSON recebido
        $payload = $request->getContent();
        if (empty($payload)) {
            Log::error("Payload da requisição está vazio.");
            return response()->json(['message' => 'Empty payload'], 400);
        }
        $eventData = json_decode($payload, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("Falha ao decodificar o JSON: " . json_last_error_msg());
            return response()->json(['message' => 'Invalid JSON'], 400);
        }
        Log::channel('events')->info($request->getContent());
        if (!isset($eventData['event'])) {
            Log::error("Estrutura do evento inválida: chave 'event' ausente.");
            return response()->json(['message' => 'Invalid event structure'], 400);
        }
        $event = $eventData['event'];
        // Inicializa variáveis
        $subscription = $event['subscription'] ?? null;
        if (!$subscription) {
            Log::error("Subscription não encontrada no evento.");
            return response()->json(['message' => 'Subscription not found'], 400);
        }
        $logData = $event['log'] ?? null;
        if (!$logData) {
            Log::error("Dados de log não encontrados no evento.");
            return response()->json(['message' => 'Log data not found'], 400);
        }
        // Verifica se o pagamento está diretamente no log ou sob a chave da subscription
        $itemIterate = $subscription;
        if ($itemIterate == 'brcode-payment' || $itemIterate == 'boleto-payment') {
            $itemIterate = 'payment';
        }
        $payment = $logData[$itemIterate] ?? $logData;
        if (!$payment) {
            Log::error("Dados de pagamento não encontrados no log para a subscription {$subscription}.");
            return response()->json(['message' => 'Payment data not found'], 400);
        }
        $id = null;
        if ($subscription === 'deposit') {
            if (isset($payment['tags']) && is_array($payment['tags'])) {
                foreach ($payment['tags'] as $tag) {
                    if (strpos($tag, 'dynamic-brcode/') === 0) {
                        // Extrai apenas o UUID da tag 'dynamic-brcode/'
                        $id = substr($tag, strlen('dynamic-brcode/'));
                        Log::info("ID extraído da tag 'dynamic-brcode/': {$id}");
                        break;
                    }
                }
            } else {
                Log::warning("Tags não encontradas ou não são um array no pagamento.");
            }
            // Se não encontrar o dynamic-brcode, pega o ID padrão
            if (!$id) {
                $id = $payment['id'] ?? null;
                Log::info("ID extraído do pagamento: {$id}");
            }
        } else {
            $id = $payment['id'] ?? null;
            Log::info("ID extraído do pagamento: {$id}");
        }
        if (!$id) {
            Log::error("ID de pagamento não encontrado.");
            return response()->json(['message' => 'Payment ID not found'], 400);
        }
        $transaction = Transaction::where('externalId', $id)->first();
        if (!$transaction) {
            Log::warning("Transação não encontrada para o ID: {$id}");
            return response()->json(['message' => 'Transaction not found'], 404);
        }
        $type = $logData['type'] ?? null;
        if (!$type) {
            Log::error("Tipo de log não encontrado no evento.");
            return response()->json(['message' => 'Log type not found'], 400);
        }

        try {
            DB::transaction(function () use ($transaction, $type, $subscription, $payment) {
                // Atualiza status da transação
                // Processa os diferentes tipos de subscription
                switch ($subscription) {
                        // RECEBIMENTOS = ENTRADA
                    case "deposit":
                        // Defina o mapeamento entre tipos e status
                        $mapping = [
                            'created'    => Transaction::STATUS_CRIADO,
                            'creating'   => Transaction::STATUS_CRIADO,
                            'processing' => Transaction::STATUS_PROCESSAMENTO,
                            'sending'    => Transaction::STATUS_PROCESSAMENTO,
                            'canceled'   => Transaction::STATUS_CANCELADO,
                            'failed'     => Transaction::STATUS_FALHOU,
                            'success'    => Transaction::STATUS_SUCESSO,
                            'paid'       => Transaction::STATUS_SUCESSO,
                            'credited'   => Transaction::STATUS_SUCESSO,
                            'overdue'    => Transaction::STATUS_EXPIRADO,
                            'unpaid'     => Transaction::STATUS_EXPIRADO,
                        ];
                        // Verifique se o tipo recebido existe no mapeamento
                        if (array_key_exists($type, $mapping)) {
                            $newStatus = $mapping[$type];
                            $transaction->status = $newStatus;
                            $transaction->account_number = $payment['accountNumber'] ?? null;
                            $transaction->bank_code = $payment['bankCode'] ?? null;
                            $transaction->branch_code = $payment['branchCode'] ?? null;
                            $transaction->name = $payment['name'] ?? null;
                            $transaction->document_number = $payment['taxId'] ?? null;
                            $transaction->save();
                        } else {
                            // Lide com o caso em que o tipo não está mapeado
                            Log::warning("Tipo de transação desconhecido: {$type}");
                        }

                        if ($transaction->description == 'Ativação conta' && $type == 'credited') {
                            $user = $transaction->user;
                            $ralbank = Split::where('title', 'like', '%Polocal%')->first();
                            $accountSplit = $ralbank->recebedor;
                            //SPLIT PAGAMENTO ralbank R$ 22,00
                            $ralbank = new Transaction([
                                'user_id' => $accountSplit->id,
                                'code' => $transaction->id,
                                'amount' => 22.00,
                                'status' => Transaction::STATUS_SUCESSO,
                                'externalId' =>  null,
                                'name' => $user->name,
                                'status' => Transaction::STATUS_SUCESSO,
                                'document_number' => $user->document,
                                'bank_code' =>  'Polocal Bank',
                                'branch_code' =>  $user->agency->number,
                                'account_number' =>  $user->account,
                                'description' => 'Split ativação de conta',
                                'type' => Transaction::TYPE_TRANSFER,
                                'operacao' => Transaction::OPERACAO_CREDIT,
                                'method' => Transaction::TYPE_TRANSFER,
                            ]);
                            $ralbank->save();

                            $service = UserServices::where('payment_id', $ralbank->id)->where('user_id', $user->id)->first();
                            if ($service) {
                                $service->status = 'active';
                                $service->save();
                            }
                            //SPLIT PAGAMENTO R$ 28,00
                            $ativacao = new Transaction([
                                'user_id' => $user->id,
                                'code' => $transaction->id,
                                'amount' => 28.00,
                                'status' => Transaction::STATUS_SUCESSO,
                                'externalId' =>  null,
                                'name' => $user->name,
                                'document_number' => $user->document,
                                'bank_code' =>  'Polocal Bank',
                                'branch_code' =>  $user->agency->number,
                                'account_number' =>  $user->account,
                                'description' => 'Debito ativação de conta',
                                'type' => Transaction::TYPE_TRANSFER,
                                'operacao' => Transaction::OPERACAO_DEBIT,
                                'method' => Transaction::TYPE_TRANSFER,
                            ]);
                            $ativacao->save();

                            $user->status = 'active';
                            $user->save();
                        }

                        break;
                    case "invoice":
                        // Defina o mapeamento entre tipos e status
                        $mapping = [
                            'created'    => Transaction::STATUS_CRIADO,
                            'creating'   => Transaction::STATUS_CRIADO,
                            'processing' => Transaction::STATUS_PROCESSAMENTO,
                            'sending'    => Transaction::STATUS_PROCESSAMENTO,
                            'canceled'   => Transaction::STATUS_CANCELADO,
                            'failed'     => Transaction::STATUS_FALHOU,
                            'success'    => Transaction::STATUS_SUCESSO,
                            'paid'       => Transaction::STATUS_SUCESSO,
                            'credited'   => Transaction::STATUS_SUCESSO,
                            'overdue'    => Transaction::STATUS_EXPIRADO,
                            'unpaid'     => Transaction::STATUS_EXPIRADO,
                        ];
                        // Verifique se o tipo recebido existe no mapeamento
                        if (array_key_exists($type, $mapping)) {
                            $newStatus = $mapping[$type];
                            $transaction->status = $newStatus;
                            $transaction->account_number = $payment['accountNumber'] ?? null;
                            $transaction->bank_code = $payment['bankCode'] ?? null;
                            $transaction->branch_code = $payment['branchCode'] ?? null;
                            $transaction->name = $payment['name'] ?? null;
                            $transaction->document_number = $payment['taxId'] ?? null;
                            $transaction->save();
                        } else {
                            // Lide com o caso em que o tipo não está mapeado
                            Log::warning("Tipo de transação desconhecido: {$type}");
                        }
                        break;
                    case "boleto":
                        // Defina o mapeamento entre tipos e status
                        $mapping = [
                            'created'    => Transaction::STATUS_CRIADO,
                            'creating'   => Transaction::STATUS_CRIADO,
                            'processing' => Transaction::STATUS_PROCESSAMENTO,
                            'sending'    => Transaction::STATUS_PROCESSAMENTO,
                            'canceled'   => Transaction::STATUS_CANCELADO,
                            'failed'     => Transaction::STATUS_FALHOU,
                            'success'    => Transaction::STATUS_SUCESSO,
                            'paid'       => Transaction::STATUS_SUCESSO,
                            'credited'   => Transaction::STATUS_SUCESSO,
                            'overdue'    => Transaction::STATUS_EXPIRADO,
                            'unpaid'     => Transaction::STATUS_EXPIRADO,
                        ];
                        // Verifique se o tipo recebido existe no mapeamento
                        if (array_key_exists($type, $mapping)) {
                            $newStatus = $mapping[$type];
                            $transaction->status = $newStatus;
                            $transaction->account_number = $payment['accountNumber'] ?? null;
                            $transaction->bank_code = $payment['bankCode'] ?? null;
                            $transaction->branch_code = $payment['branchCode'] ?? null;
                            $transaction->name = $payment['name'] ?? null;
                            $transaction->document_number = $payment['taxId'] ?? null;
                            $transaction->save();
                        } else {
                            // Lide com o caso em que o tipo não está mapeado
                            Log::warning("Tipo de transação desconhecido: {$type}");
                        }
                        break;
                        // PAGAMENTOS = SAÍDA
                    case "transfer":
                        // Defina o mapeamento entre tipos e status
                        $mapping = [
                            'created'    => Transaction::STATUS_CRIADO,
                            'creating'   => Transaction::STATUS_CRIADO,
                            'processing' => Transaction::STATUS_PROCESSAMENTO,
                            'sending'    => Transaction::STATUS_PROCESSAMENTO,
                            'canceled'   => Transaction::STATUS_CANCELADO,
                            'failed'     => Transaction::STATUS_FALHOU,
                            'success'    => Transaction::STATUS_SUCESSO,
                            'paid'       => Transaction::STATUS_SUCESSO,
                            'credited'   => Transaction::STATUS_SUCESSO,
                            'overdue'    => Transaction::STATUS_EXPIRADO,
                            'unpaid'     => Transaction::STATUS_EXPIRADO,
                        ];
                        // Verifique se o tipo recebido existe no mapeamento
                        if (array_key_exists($type, $mapping)) {
                            $newStatus = $mapping[$type];
                            $transaction->status = $newStatus;
                            $transaction->account_number = $payment['accountNumber'];
                            $transaction->bank_code = $payment['bankCode'];
                            $transaction->branch_code = $payment['branchCode'];
                            $transaction->name = $payment['name'];
                            $transaction->document_number = $payment['taxId'];
                            $transaction->save();
                        } else {
                            // Lide com o caso em que o tipo não está mapeado
                            Log::warning("Tipo de transação desconhecido: {$type}");
                        }
                        break;
                    case "brcode-payment":
                        // Defina o mapeamento entre tipos e status
                        $mapping = [
                            'created'    => Transaction::STATUS_CRIADO,
                            'creating'   => Transaction::STATUS_CRIADO,
                            'processing' => Transaction::STATUS_PROCESSAMENTO,
                            'sending'    => Transaction::STATUS_PROCESSAMENTO,
                            'canceled'   => Transaction::STATUS_CANCELADO,
                            'failed'     => Transaction::STATUS_FALHOU,
                            'success'    => Transaction::STATUS_SUCESSO,
                            'paid'       => Transaction::STATUS_SUCESSO,
                            'credited'   => Transaction::STATUS_SUCESSO,
                            'overdue'    => Transaction::STATUS_EXPIRADO,
                            'unpaid'     => Transaction::STATUS_EXPIRADO,
                        ];
                        // Verifique se o tipo recebido existe no mapeamento
                        if (array_key_exists($type, $mapping)) {
                            $newStatus = $mapping[$type];
                            $transaction->status = $newStatus;
                            $transaction->account_number = $payment['accountNumber'] ?? null;
                            $transaction->bank_code = $payment['bankCode'] ?? null;
                            $transaction->branch_code = $payment['branchCode'] ?? null;
                            $transaction->name = $payment['name'] ?? null;
                            $transaction->document_number = $payment['taxId'] ?? null;
                            $transaction->save();
                        } else {
                            // Lide com o caso em que o tipo não está mapeado
                            Log::warning("Tipo de transação desconhecido: {$type}");
                        }
                        break;
                    case "boleto-payment":
                        // Defina o mapeamento entre tipos e status
                        $mapping = [
                            'created'    => Transaction::STATUS_CRIADO,
                            'creating'   => Transaction::STATUS_CRIADO,
                            'processing' => Transaction::STATUS_PROCESSAMENTO,
                            'sending'    => Transaction::STATUS_PROCESSAMENTO,
                            'canceled'   => Transaction::STATUS_CANCELADO,
                            'failed'     => Transaction::STATUS_FALHOU,
                            'success'    => Transaction::STATUS_SUCESSO,
                            'paid'       => Transaction::STATUS_SUCESSO,
                            'credited'   => Transaction::STATUS_SUCESSO,
                            'overdue'    => Transaction::STATUS_EXPIRADO,
                            'unpaid'     => Transaction::STATUS_EXPIRADO,
                        ];
                        // Verifique se o tipo recebido existe no mapeamento
                        if (array_key_exists($type, $mapping)) {
                            $newStatus = $mapping[$type];
                            $transaction->status = $newStatus;
                            $transaction->account_number = $payment['accountNumber'] ?? null;
                            $transaction->bank_code = $payment['bankCode'] ?? null;
                            $transaction->branch_code = $payment['branchCode'] ?? null;
                            $transaction->name = $payment['name'] ?? null;
                            $transaction->document_number = $payment['taxId'] ?? null;
                            $transaction->save();
                        } else {
                            // Lide com o caso em que o tipo não está mapeado
                            Log::warning("Tipo de transação desconhecido: {$type}");
                        }
                        break;
                        break;
                    default:
                        Log::warning("Subscription desconhecida: {$subscription}");
                        return response()->json(['message' => 'Unknown subscription'], 400);
                }
            });
        } catch (\Exception $e) {
            Log::error("Erro ao processar a transação: " . $e->getMessage());
            return response()->json(['message' => 'Transaction processing failed'], 500);
        }
        return response()->json(['message' => 'Transaction processed successfully'], 200);
    }
}

<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StarbankService;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\Type;
use Illuminate\Support\Facades\Log;


class ActiveAccountController extends Controller
{
   public function checkout(Request $request)
   {
      // Obtendo o usuário autenticado através do token
      $user = auth('api')->user();  // Ou auth()->user()

      // Se o usuário já estiver ativo, retorna uma mensagem de erro
      if ($user->status == 'active') {
         return response()->json([
            'message' => 'A conta já está ativa.',
         ], 400);
      }

      $starkbankService = new StarbankService();
      DB::beginTransaction();

      try {
         $amount = 2800; // R$ 28,00 em centavos
         // Verifica se já existe uma transação de ativação de conta
         $transaction = Transaction::where('user_id', $user->id)
            ->where('description', 'like', '%Ativação conta%')
            ->first();

         if (!$transaction) {
            // Cria o QR Code para o recebimento via Pix
            $qrcode = $starkbankService->criaQrCodeRecebimento($amount, $user);
            // Salvar a transação na base de dados
            $transaction = new Transaction([
               'user_id' => $user->id,
               'code' => $qrcode[0]->id,
               'amount' => $amount / 100,
               'status' => Transaction::STATUS_CRIADO,
               'externalId' => $qrcode[0]->uuid,
               'description' => 'Ativação conta',
               'name' => 'Ativação conta',
               'type' => Transaction::TYPE_DYNAMIC_BRCODE,
               'operacao' => Transaction::OPERACAO_CREDIT,
               'method' => Transaction::METHOD_PIX,
               'fee' => 0.04,
            ]);
            $transaction->save();
         } else {
            // Busca o QR Code existente
            $qrcode = $starkbankService->buscaQrCodeRecebimento($transaction->externalId, $user);
            // Gera o preview do pagamento
            $pixPreview = $starkbankService->pagamentoPreview($qrcode->id);
            // Se o status do pagamento for "expired", deleta a transação e cria uma nova
            if ($pixPreview[0]->payment->status == 'expired') {
               $transaction->delete(); // Deleta a transação anterior
               // Cria um novo QR Code e transação
               $brcode = $starkbankService->criaQrCodeRecebimento($amount, $user);
               $transaction = new Transaction([
                  'user_id' => $user->id,
                  'code' => $qrcode[0]->id,
                  'amount' => $amount / 100,
                  'status' => Transaction::STATUS_CRIADO,
                  'externalId' => $qrcode[0]->uuid,
                  'name' => 'Ativação conta',
                  'description' => 'Ativação conta',
                  'type' => Transaction::TYPE_DYNAMIC_BRCODE,
                  'operacao' => Transaction::OPERACAO_CREDIT,
                  'method' => Transaction::METHOD_PIX,
                  'fee' => 0.04,
               ]);
               $transaction->save();
            }
         }
         // Busca o QR Code atualizado
         $qrcode = $starkbankService->buscaQrCodeRecebimento($transaction->externalId, $user);
         // Confirma a transação no banco de dados
         DB::commit();
         // Retorna o QR Code e a transação em formato JSON
         return response()->json([
            'message' => 'QR Code gerado com sucesso para ativação de conta.',
            'qrcode' => $qrcode,
            'transaction' => $transaction,
         ], 201);
      } catch (\Exception $e) {
         DB::rollBack();
         Log::error('Erro ao processar a ativação de conta: ' . $e->getMessage());
         // Retorna mensagem de erro em JSON
         return response()->json([
            'message' => 'Erro ao processar a ativação de conta.',
            'error' => $e->getMessage(),
         ], 500);
      }
   }


   public function checkAccount(Request $request)
   {
      // Verifica se o usuário está autenticado
      $user = auth('api')->user(); // Ou auth()->user()

      if ($user) {
         // Retorna o status da conta em formato JSON
         return response()->json([
            'message' => 'Usuário autenticado.',
            'status' => $user->status,
         ], 200);
      }

      // Se o usuário não estiver autenticado, retorna um erro
      return response()->json([
         'message' => 'Usuário não autenticado.',
      ], 401);
   }
}

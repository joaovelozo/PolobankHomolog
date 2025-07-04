<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Validator;
use App\Services\StarbankService;

class TransactionsController extends Controller
{
    public function index()
    {
        // Recupera o usuário autenticado
        $user = Auth::user();
        // Caso não tenha um usuário autenticado
        if (!$user) {
            return response()->json([
                'error' => 'Usuário não autenticado.'
            ], 401);
        }
        // Busca as transações onde o usuário é o remetente ou o destinatário
        $transactions = Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'DESC')
            ->get()->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'amount' => $transaction->amount,
                    'status' => $transaction->status,
                    'name' => $transaction->name,
                    'document_number' => $transaction->document_number,
                    'document_number' => $transaction->document_number,
                    'status_description' => $transaction->getStatusDescription(),
                    'operacao_description' => $transaction->getOperacaoDescription(),
                    'metodo_description' => $transaction->getMetodoDescription(),
                    'metodo_description' => $transaction->getMetodoDescription(),
                    'type_description' => $transaction->getTypeDescription(),
                    'created_at' => $transaction->created_at,
                ];
            });

        // Verifica se as transações estão definidas e se estão vazias
        if (empty($transactions)) {
            return response()->json([
                'access_token' => $user->createToken($user->name . '-AuthToken')->plainTextToken,
                'transactions' => [], // Retorna um array vazio
                'message' => 'Nenhuma transação encontrada.' // Mensagem indicando que não há transações
            ]);
        }
        // Retorna o token de acesso e as transações do usuário
        return response()->json([
            'access_token' => $user->createToken($user->name . '-AuthToken')->plainTextToken,
            'transactions' => $transactions,
        ]);
    }


    public function receive_qrcode_create(Request $request)
    {
        // Validação do request com mensagens personalizadas
        $validator = Validator::make($request->all(), [
            'amount' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    // Verifica se o valor é um formato monetário válido
                    if (!preg_match('/^\d{1,3}(?:\.\d{3})*(?:,\d{2})?$/', $value)) {
                        $fail('O campo ' . $attribute . ' tem um formato inválido.');
                    }
                },
            ],
        ], [
            'amount.required' => 'O valor é obrigatório.',
            'amount.string' => 'O valor deve ser uma string.',
        ]);

        // Se a validação falhar, retorna os erros
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erro na validação dos dados.',
                'errors' => $validator->errors(),
            ], 422);
        }
        $user = auth('api')->user();  // Ou auth()->user()
        $starkbankService = new StarbankService();
        $amount = $this->convertAmountToInt($request->amount);

        // Inicia uma transação no banco de dados
        DB::beginTransaction();

        try {
            $qrcode = $starkbankService->criaQrCodeRecebimento($amount, $user);
            // Salvar a transação na base de dados
            $transaction = new Transaction([
                'user_id' => Auth::id(),
                'code' => $qrcode[0]->id,
                'amount' => $amount / 100,
                'status' => Transaction::STATUS_CRIADO,
                'externalId' => $qrcode[0]->uuid,
                'description' => 'Gerou QR Code de pagamento: #' . $qrcode[0]->id,
                'type' => Transaction::TYPE_DYNAMIC_BRCODE,
                'operacao' => Transaction::OPERACAO_CREDIT,
                'method' => Transaction::METHOD_PIX,
                'fee' => 0.04,
            ]);
            $transaction->save();
            // Comita a transação no banco de dados
            DB::commit();

            // Retorna a resposta de sucesso
            return response()->json([
                'message' => 'QR Code criado com sucesso.',
                'transaction' => $transaction,
            ], 201);
        } catch (\Exception $e) {
            // Faz rollback em caso de erro e registra no log
            DB::rollBack();
            Log::error('Erro ao criar QR Code: ' . $e->getMessage());

            return response()->json([
                'message' => 'Erro ao criar QR Code.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Função para converter o valor monetário para centavos.
     */
    private function convertAmountToInt($amount)
    {
        // Remove os pontos e substitui a vírgula por um ponto
        $amount = str_replace('.', '', $amount);
        $amount = str_replace(',', '.', $amount);
        return (int)($amount * 100); // Converte para centavos
    }

    public function receive_qrcode_view($id)
    {
        // Obtendo o usuário autenticado através do token
        $user = auth('api')->user();  // Ou auth()->user()

        if (!$user) {
            return response()->json([
                'message' => 'Usuário não autenticado.',
            ], 401);
        }

        // Procurando a transação com o ID fornecido e garantindo que o usuário autenticado seja o remetente
        $transaction = Transaction::where('user_id', $user->id)->find($id);

        if (!$transaction) {
            return response()->json([
                'message' => 'QR Code não encontrado.',
            ], 400);
        }

        try {
            // Buscando o QR Code do serviço Starbank
            $starkbankService = new StarbankService();
            $brcode = $starkbankService->buscaQrCodeRecebimento($transaction->externalId, $user);

            // Retornando o QR Code em uma resposta JSON
            return response()->json([
                'message' => 'QR Code encontrado com sucesso.',
                'brcode' => $brcode,
                'transaction' => $transaction
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar QR Code: ' . $e->getMessage());

            return response()->json([
                'message' => 'Erro ao buscar QR Code.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function receive_qrcode_extract()
    {
        // Obtendo o usuário autenticado através do token
        $user = auth('api')->user();  // Ou auth()->user()

        if (!$user) {
            return response()->json([
                'message' => 'Usuário não autenticado.',
            ], 401);
        }
        $transactions = Transaction::where('user_id', Auth::id())->where('method', Transaction::METHOD_PIX)->where('operacao', Transaction::OPERACAO_CREDIT)->orderBy('created_at', 'DESC')->get();
        // Retornando as transações em formato JSON
        return response()->json([
            'message' => 'Transações encontradas com sucesso.',
            'transactions' => $transactions,
        ], 200);
    }


    public function payment_pix_copy_paste(Request $request)
    {
        // Validação do request
        $request->validate([
            'codigo_pix' => 'required|string',
            'amount' => ['string', function ($attribute, $value, $fail) {
                // Verifica se o valor é um formato monetário válido
                if (!preg_match('/^\d{1,3}(?:\.\d{3})*(?:,\d{2})?$/', $value)) {
                    $fail('O campo ' . $attribute . ' tem um formato inválido.');
                }
            }],
        ]);
        $user = auth('api')->user();

        $transactionExists = Transaction::where('user_id', Auth::id())
            ->where('operacao', Transaction::OPERACAO_DEBIT)
            ->whereIn('status', [Transaction::STATUS_CRIADO, Transaction::STATUS_PROCESSAMENTO])
            ->exists();


        if ($transactionExists) {
            return response()->json(['error' => 'Você já possui uma transação em andamento. Aguarde a conclusão antes de iniciar uma nova.'], 400);
        }

        $starkbankService = new StarbankService();
        DB::beginTransaction();

        try {
            // Obtendo o pré-pagamento via código PIX
            $pagamentoPreview = $starkbankService->pagamentoPreview($request->codigo_pix);

            // Se o boleto não for encontrado ou o código for inválido
            if (!$pagamentoPreview || count($pagamentoPreview) == 0) {
                return response()->json(['error' => 'Pagamento não encontrado ou código inválido.'], 400);
            }
            $amount = ($pagamentoPreview[0]->payment->amount > 0) ? $pagamentoPreview[0]->payment->amount : 0;
            if ($amount == 0 && isset($request->amount) && $request->amount > 0) {
                $amount = convertAmountToInt($request->amount);
            }

            if ($amount == 0) {
                return response()->json(['error' => 'Valor a ser pago não informado'], 400);
            }

            // Verifica se o usuário tem saldo suficiente
            if ($amount > convertAmountToInt($user->balance())) {
                return response()->json([
                    'message' => 'Saldo insuficiente para efetuar o pagamento.'
                ], 400);
            }
            
            // Criação do pagamento PIX
            $pix = $starkbankService->criaQrCodePagamento(
                $request->codigo_pix,
                $amount,
                $pagamentoPreview[0]->payment->taxId,
                'Pagamento PIX para ' . $pagamentoPreview[0]->payment->name,
                $user
            );
            // Salvar a transação na base de dados
            $transaction = new Transaction([
                'user_id' => Auth::id(),
                'name' => $pagamentoPreview[0]->payment->name,
                'code' => $request->codigo_pix,
                'amount' => $amount / 100,
                'status' => Transaction::STATUS_CRIADO,
                'externalId' => $pix[0]->id,
                'document_number' =>  $pagamentoPreview[0]->payment->taxId,
                'bank_code' =>   $pagamentoPreview[0]->payment->bankCode,
                'description' => 'Pagamento de pix: #' . $pix[0]->id,
                'type' => 'transfer',
                'operacao' => Transaction::OPERACAO_DEBIT,
                'method' => Transaction::METHOD_PIX,
                'fee' => $pix[0]->fee / 100,
            ]);

            $transaction->save();
            DB::commit();
            // Retornar mensagem de sucesso em JSON
            return response()->json([
                'message' => 'Pagamento em processamento!',
                'transaction' => $transaction
            ], 201);
        } catch (\StarkBank\Error\InputErrors $e) {
            DB::rollBack();
            // Captura erros específicos da API StarkBank
            $errors = [];
            foreach ($e->errors as $error) {
                $errors[] = "Código: " . $error->errorCode . " - Mensagem: " . $error->errorMessage;
            }
            Log::error('Erro ao processar pagamento via StarkBank: ' . implode(", ", $errors));

            return response()->json([
                'message' => 'Erro ao processar o pagamento.',
                'errors' => $errors
            ], 400);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao processar pagamento do pix: ' . $e->getMessage());

            return response()->json([
                'message' => 'Erro ao processar o pagamento.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function payment_pix_extract()
    {
        // Obtendo o usuário autenticado através do token
        $user = auth('api')->user();  // Ou auth()->user()

        if (!$user) {
            return response()->json([
                'message' => 'Usuário não autenticado.',
            ], 401);
        }
        $transactions = Transaction::where('user_id', Auth::id())->where('method', Transaction::METHOD_PIX)->where('operacao', Transaction::OPERACAO_DEBIT)->orderBy('created_at', 'DESC')->get();
        // Retornando as transações em formato JSON
        return response()->json([
            'message' => 'Transações PIX de saída encontradas com sucesso.',
            'transactions' => $transactions,
        ], 200);
    }

    public function payment_pix_key(Request $request)
    {
        // Validação do request
        $request->validate([
            'amount' => ['required', 'string', function ($attribute, $value, $fail) {
                // Verifica se o valor é um formato monetário válido
                if (!preg_match('/^\d{1,3}(?:\.\d{3})*(?:,\d{2})?$/', $value)) {
                    $fail('O campo ' . $attribute . ' tem um formato inválido.');
                }
            }],
            'chave_pix' => 'required|string'
        ]);
        if (preg_match('/^\d{11}$/', $request->chave_pix)) {
            if (!checkIsCPF($request->chave_pix)) {
                $request->merge([
                    'chave_pix' => '+55' . $request->chave_pix
                ]);
            }
        }

        $user = auth('api')->user();

        $transactionExists = Transaction::where('user_id', Auth::id())
            ->where('operacao', Transaction::OPERACAO_DEBIT)
            ->whereIn('status', [Transaction::STATUS_CRIADO, Transaction::STATUS_PROCESSAMENTO])
            ->exists();

        if ($transactionExists) {
            return response()->json(['error' => 'Você já possui uma transação em andamento. Aguarde a conclusão antes de iniciar uma nova.'], 400);
        }


        // Converte o valor monetário para centavos
        $amount = convertAmountToInt($request->amount);

        $starkbankService = new StarbankService();
        DB::beginTransaction();

        try {
            // Busca a chave PIX no serviço StarkBank
            $pix = $starkbankService->getDictKey($request->chave_pix);

            // Se a chave PIX não for encontrada
            if (!$pix) {
                return response()->json([
                    'message' => 'Chave PIX não encontrada.'
                ], 400);
            }

            // Verifica se o usuário autenticado tem saldo suficiente
            if ($amount > convertAmountToInt($user->balance())) {
                return response()->json([
                    'message' => 'Saldo insuficiente para realizar a transferência.'
                ], 400);
            }

            // Criação da transferência PIX
            $transferencia = $starkbankService->tranferencia(
                $amount,
                $pix->ispb,
                $pix->branchCode,
                $pix->accountNumber,
                $pix->taxId,
                $pix->name,
                $user
            );

            // Salvar a transação na base de dados
            $transaction = new Transaction([
                'user_id' => Auth::id(),
                'name' => $pix->name,
                'code' => $request->chave_pix,
                'amount' => $amount / 100,
                'status' => Transaction::STATUS_CRIADO,
                'externalId' => $transferencia[0]->id,
                'document_number' =>  $pix->taxId,
                'bank_code' =>  $pix->ispb,
                'branch_code' =>  $pix->branchCode,
                'account_number' =>  $pix->accountNumber,
                'description' => 'Transferência PIX: #' . $transferencia[0]->id,
                'type' => 'transfer',
                'operacao' => Transaction::OPERACAO_DEBIT,
                'method' => Transaction::METHOD_PIX,
                'fee' => $transferencia[0]->fee / 100,
            ]);

            $transaction->save();

            DB::commit();

            // Retornar mensagem de sucesso em JSON
            return response()->json([
                'message' => 'Transferência PIX em processamento!',
                'transaction' => $transaction
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao processar transferência PIX: ' . $e->getMessage());

            // Retornar mensagem de erro em JSON
            return response()->json([
                'message' => 'Erro ao processar a transferência PIX.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function invoice($transactionId)
    {
        // Verifica se a transação existe
        $transaction = Transaction::findOrFail($transactionId);

        // Obtendo o usuário autenticado
        $user = auth('api')->user();
        $date = now()->format('d/m/Y H:i:s');

        // Gera o PDF com os dados da transação e do usuário
        $pdf = \PDF::loadView('users.invoice.invoice', compact('transaction', 'user', 'date'));

        // Converte o PDF em string base64
        $pdfContent = $pdf->output();
        $pdfBase64 = base64_encode($pdfContent);

        // Retorna o PDF em base64 para que o aplicativo possa lidar com ele
        return response()->json([
            'message' => 'Comprovante gerado com sucesso.',
            'invoice_pdf_base64' => $pdfBase64,
            'file_name' => 'comprovante_pix.pdf',
        ], 200);
    }



    public function transfer_accounts_store(Request $request)
    {
        // Validação do request
        $request->validate([
            'amount' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    // Verifica se o valor é um formato monetário válido
                    if (!preg_match('/^\d{1,3}(?:\.\d{3})*(?:,\d{2})?$/', $value)) {
                        $fail('O campo ' . $attribute . ' tem um formato inválido.');
                    }
                }
            ],
            'account' => 'required|string|exists:users,account', // Certifica que a conta existe
        ]);

        // Converte o valor para centavos
        $amount = convertAmountToInt($request->amount);
        $account = $request->account;
        $sender = auth('api')->user(); // Obtém o usuário autenticado via token
        $receiver = User::where('account', $account)->first();

        // Verificação de erros
        if (!$receiver) {
            return response()->json([
                'message' => 'Conta inválida. Verifique se o número está correto e tente novamente.',
            ], 400);
        }

        if ($amount > convertAmountToInt($sender->balance())) {
            return response()->json([
                'message' => 'Saldo insuficiente.',
            ], 400);
        }

        try {
            // Inicia uma transação no banco de dados
            DB::beginTransaction();

            $saida = new Transaction([
                'user_id' => Auth::id(),
                'code' => $account,
                'amount' => $amount / 100,
                'status' => Transaction::STATUS_SUCESSO,
                'externalId' =>  null,
                'name' => $receiver->name,
                'status' => Transaction::STATUS_SUCESSO,
                'document_number' =>  $receiver->document,
                'bank_code' =>  'Polocal Bank',
                'branch_code' =>  $receiver->agency->number,
                'account_number' =>  $receiver->account,
                'description' => 'Fez uma transferencia para: #' . $receiver->id,
                'type' => Transaction::TYPE_TRANSFER,
                'operacao' => Transaction::OPERACAO_DEBIT,
                'method' => Transaction::TYPE_TRANSFER,
            ]);
            $saida->save();

            $entrada = new Transaction([
                'user_id' => $receiver->id,
                'code' => $account,
                'amount' => $amount / 100,
                'status' => Transaction::STATUS_SUCESSO,
                'externalId' =>  null,
                'name' => $sender->name,
                'status' => Transaction::STATUS_SUCESSO,
                'document_number' =>  $sender->document,
                'bank_code' =>  'Polocal Bank',
                'branch_code' =>  $sender->agency->number,
                'account_number' =>  $sender->account,
                'description' => 'Fez recebeu uma transferencia de: #' . $sender->id,
                'type' => Transaction::TYPE_TRANSFER,
                'operacao' => Transaction::OPERACAO_CREDIT,
                'method' => Transaction::TYPE_TRANSFER,
            ]);

            $entrada->save();

            // Comita a transação
            DB::commit();

            // Retorna mensagem de sucesso em JSON
            return response()->json([
                'message' => 'Transferência concluída com sucesso.',
                'transaction' => $saida
            ], 201);
        } catch (\Exception $e) {
            // Rollback em caso de erro
            DB::rollBack();
            Log::error('Erro ao realizar transferência: ' . $e->getMessage());

            // Retorna mensagem de erro em JSON
            return response()->json([
                'message' => 'Erro ao realizar a transferência.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function transfer_extract()
    {
        // Obtém o usuário autenticado
        $user = auth('api')->user();
        $transactions = Transaction::where('user_id',  Auth::id())->where('operacao', Transaction::OPERACAO_DEBIT)->where('type',  Transaction::TYPE_TRANSFER)->where('method', Transaction::METHOD_TRANSFER)->orderBy('created_at', 'DESC')->get();
        // Retorna as transações em formato JSON
        return response()->json([
            'message' => 'Transações de transferência recuperadas com sucesso.',
            'transactions' => $transactions
        ], 200);
    }


    public function receive_boleto_create(Request $request)
    {
        // Validação do request
        $request->validate([
            'amount' => ['required', 'string', function ($attribute, $value, $fail) {
                // Verifica se o valor é um formato monetário válido
                if (!preg_match('/^\d{1,3}(?:\.\d{3})*(?:,\d{2})?$/', $value)) {
                    $fail('O campo ' . $attribute . ' tem um formato inválido.');
                }
            }],
        ]);

        $starkbankService = new StarbankService();
        DB::beginTransaction();

        try {
            $user = auth('api')->user(); // Obtém o usuário autenticado via API

            // Valida o CPF do usuário
            if (!isValidCPF($user->document)) {
                return response()->json([
                    'message' => 'O CPF associado à sua conta é inválido. Por favor, atualize suas informações de CPF em "Minha Conta" para corrigir o problema.',
                ], 400);
            }

            // Valida o CEP do usuário
            $cep = str_replace('.', '', $user->zipcode);
            if (!isValidCEP($cep)) {
                return response()->json([
                    'message' => 'O CEP associado à sua conta é inválido. Por favor, atualize seu CEP em "Minha Conta" para corrigir o problema.',
                ], 400);
            }

            // Converte o valor para centavos
            $amount = convertAmountToInt($request->amount);

            // Dados para o boleto
            $name = $user->name;
            $taxId = $user->document;
            $rua = $user->address;
            $complemento = $user->number . ' - ' . $user->complement;
            $bairro = $user->neighborhood;
            $cidade = $user->city;
            $estado = $user->state;
            $cep = $cep;

            // Gera o boleto de recebimento
            $boleto = $starkbankService->criaBoletoRecebimento(
                $amount,
                $name,
                $taxId,
                $rua,
                $complemento,
                $bairro,
                $cidade,
                $estado,
                $cep,
                $user
            );

            // Salvar a transação na base de dados
            $transaction = new Transaction([
                'user_id' => Auth::id(),
                'code' => $boleto[0]->barCode,
                'amount' => $amount / 100,
                'status' => Transaction::STATUS_CRIADO,
                'externalId' => $boleto[0]->id,
                'description' => 'Boleto criado para pagamento: #' . $boleto[0]->id,
                'type' => 'boleto',
                'operacao' => Transaction::OPERACAO_CREDIT,
                'method' => Transaction::METHOD_BOLETO,
                'fee' => $boleto[0]->fee / 100,
            ]);
            $transaction->save();

            DB::commit();

            // Retorna a resposta de sucesso com detalhes do boleto
            return response()->json([
                'message' => 'Boleto gerado com sucesso!',
                'boleto' => $boleto[0], // Detalhes do boleto gerado
                'transaction' => $transaction
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao gerar boleto: ' . $e->getMessage());

            // Retorna mensagem de erro
            return response()->json([
                'message' => 'Erro ao gerar o boleto.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function receive_boleto_extract()
    {
        // Obtém o usuário autenticado via token
        $user = auth('api')->user();
        $transactions = Transaction::where('user_id', Auth::id())->where('method', Transaction::METHOD_BOLETO)->where('operacao', Transaction::OPERACAO_CREDIT)->orderBy('created_at', 'DESC')->get();
        // Retorna as transações em formato JSON
        return response()->json([
            'message' => 'Transações de boleto recuperadas com sucesso.',
            'transactions' => $transactions,
        ], 200);
    }


    public function receive_boleto_view($id)
    {
        // Obtém o usuário autenticado via token
        $user = auth('api')->user();

        // Busca a transação onde o usuário autenticado é o remetente
        $transaction = Transaction::where('user_id', $user->id)->find($id);

        // Verifica se a transação foi encontrada
        if (!$transaction) {
            return response()->json([
                'message' => 'Boleto não encontrado.',
            ], 400);
        }

        // Busca o boleto via serviço StarkBank
        $starkbankService = new StarbankService();
        $boleto = $starkbankService->buscaBoletoRecebimento($transaction->externalId);

        // Retorna o boleto em formato JSON
        return response()->json([
            'message' => 'Boleto encontrado com sucesso.',
            'boleto' => $boleto,
            'transaction' => $transaction
        ], 200);
    }


    public function receive_boleto_download($id)
    {
        try {
            $starkbankService = new StarbankService();
            // Gera o PDF do boleto usando a API da StarkBank
            $pdf = $starkbankService->buscaBoletoRecebimentoPDF($id);
            // Verifica se o PDF foi gerado corretamente
            if (!$pdf) {
                return response()->json([
                    'message' => 'Erro ao gerar o PDF do boleto.',
                ], 400);
            }

            // Nome do arquivo PDF
            $fileName = 'boleto-' . $id . '.pdf';
            // Retorna o arquivo PDF como resposta diretamente em base64 (para apps)
            return response()->json([
                'message' => 'Boleto gerado com sucesso.',
                'file_name' => $fileName,
                'pdf_base64' => base64_encode($pdf),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao gerar o PDF do boleto: ' . $e->getMessage());

            // Retorna mensagem de erro
            return response()->json([
                'message' => 'Erro ao baixar o PDF do boleto.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function payment_boleto_create(Request $request)
    {
        // Validação do request
        $request->validate([
            'codigo_barra' => 'required|string'
        ]);

        // Verifica se o usuário já possui uma transação em andamento
        $user = auth('api')->user(); // Obtém o usuário autenticado via token
        $transactionExists = Transaction::where('user_id', Auth::id())
            ->where('operacao', Transaction::OPERACAO_DEBIT)
            ->whereIn('status', [Transaction::STATUS_CRIADO, Transaction::STATUS_PROCESSAMENTO])
            ->exists();

        if ($transactionExists) {
            return response()->json([
                'message' => 'Você já possui uma transação em andamento. Aguarde a conclusão antes de iniciar uma nova.',
            ], 400);
        }

        $starkbankService = new StarbankService();
        DB::beginTransaction();

        try {
            // Pré-visualiza o pagamento do boleto
            $boletoPreview = $starkbankService->pagamentoPreview($request->codigo_barra);

            // Se o boleto não for encontrado ou for inválido
            if (!$boletoPreview || count($boletoPreview) == 0) {
                return response()->json([
                    'message' => 'Boleto não encontrado ou código de barras inválido.',
                ], 400);
            }

            // Verifica se o saldo do usuário é suficiente para o pagamento
            if (convertAmountToInt($boletoPreview[0]->payment->amount) > convertAmountToInt($user->balance())) {
                return response()->json([
                    'message' => 'Saldo insuficiente para pagar o boleto.',
                ], 400);
            }

            // Criação do pagamento do boleto
            $boleto = $starkbankService->criaBoletoPagamento(
                $request->codigo_barra,
                $boletoPreview[0]->payment->taxId,
                'Pagamento de boleto',
                $user
            );

            $transaction = new Transaction([
                'user_id' => Auth::id(),
                'name' => $boletoPreview[0]->payment->name,
                'code' => $request->codigo_barra,
                'amount' => $boletoPreview[0]->payment->amount / 100,
                'status' => Transaction::STATUS_CRIADO,
                'externalId' => $boleto[0]->id,
                'document_number' =>  $boletoPreview[0]->payment->taxId,
                'bank_code' =>   substr($boletoPreview[0]->payment->barCode, 0, 3),
                'description' => 'Pagamento de boleto: #' . $boleto[0]->id,
                'type' => 'transfer',
                'operacao' => Transaction::OPERACAO_DEBIT,
                'method' => Transaction::METHOD_BOLETO,
                'fee' => $boleto[0]->fee / 100,
            ]);
            $transaction->save();

            DB::commit();

            // Retorna uma resposta de sucesso
            return response()->json([
                'message' => 'Pagamento em processamento!',
                'transaction' => $transaction,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao processar pagamento do boleto: ' . $e->getMessage());

            // Retorna uma resposta de erro
            return response()->json([
                'message' => 'Erro ao processar o pagamento.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function payment_boleto_extract()
    {
        // Obtém o usuário autenticado via token
        $user = auth('api')->user();

        $transactions = Transaction::where('user_id', $user->id)->where('method', Transaction::METHOD_BOLETO)->where('operacao', Transaction::OPERACAO_DEBIT)->orderBy('created_at', 'DESC')->get();

        // Retorna as transações em formato JSON
        return response()->json([
            'message' => 'Transações de boleto recuperadas com sucesso.',
            'transactions' => $transactions,
        ], 200);
    }

    public function payment_boleto_preview(Request $request)
    {
        // Validação do request
        $request->validate([
            'codigo_barra' => 'required|string'
        ]);

        // Verifica se o usuário já possui uma transação de saída em andamento
        $user = auth('api')->user(); // Obtém o usuário autenticado via token
        $transactionExists = Transaction::where('user_id', $user->id)
            ->where('operacao', Transaction::OPERACAO_DEBIT)
            ->whereIn('status', [Transaction::STATUS_CRIADO, Transaction::STATUS_PROCESSAMENTO])
            ->exists();

        if ($transactionExists) {
            return response()->json([
                'message' => 'Você já possui uma transação em andamento. Aguarde a conclusão antes de iniciar uma nova.',
            ], 400);
        }

        // Busca a pré-visualização do boleto usando o serviço StarkBank
        $starkbankService = new StarbankService();
        $boletoPreview = $starkbankService->pagamentoPreview($request->codigo_barra);

        // Verifica se há algum erro na pré-visualização do boleto
        if (isset($boletoPreview['error']) && $boletoPreview['error']) {
            return response()->json([
                'message' => 'Boleto não encontrado ou código de barras inválido.',
            ], 400);
        }

        // Verifica se o boleto foi encontrado
        if (!$boletoPreview || count($boletoPreview) == 0) {
            return response()->json([
                'message' => 'Boleto não encontrado ou código de barras inválido.',
            ], 400);
        }

        $boletoPreview = $boletoPreview[0];

        // Verifica se o saldo do usuário é suficiente para pagar o boleto
        if (convertAmountToInt($boletoPreview->payment->amount) > convertAmountToInt($user->balance())) {
            return response()->json([
                'message' => 'Saldo insuficiente para pagar o boleto.',
            ], 400);
        }

        // Retorna a pré-visualização do boleto em formato JSON
        return response()->json([
            'message' => 'Pré-visualização do boleto gerada com sucesso.',
            'boleto_preview' => $boletoPreview
        ], 200);
    }


    public function payment_pix_preview(Request $request)
    {
        // Validação do request
        $request->validate([
            'codigo_pix' => 'required|string'
        ]);

        // Verifica se o usuário já possui uma transação de saída em andamento
        $user = auth('api')->user(); // Obtém o usuário autenticado via token
        $transactionExists = Transaction::where('user_id', Auth::id())
            ->where('operacao', Transaction::OPERACAO_DEBIT)
            ->whereIn('status', [Transaction::STATUS_CRIADO, Transaction::STATUS_PROCESSAMENTO])
            ->exists();

        if ($transactionExists) {
            return response()->json([
                'message' => 'Você já possui uma transação em andamento. Aguarde a conclusão antes de iniciar uma nova.',
            ], 400);
        }

        // Busca a pré-visualização do pagamento PIX usando o serviço StarkBank
        $starkbankService = new StarbankService();
        $pixPreview = $starkbankService->pagamentoPreview($request->codigo_pix);

        // Verifica se há algum erro na pré-visualização do pagamento PIX
        if (isset($pixPreview['error']) && $pixPreview['error']) {
            return response()->json([
                'message' => 'Pagamento não encontrado ou código PIX inválido.',
            ], 400);
        }

        // Verifica se o pagamento foi encontrado
        if (!$pixPreview || count($pixPreview) == 0) {
            return response()->json([
                'message' => 'Pagamento não encontrado ou código PIX inválido.',
            ], 400);
        }

        $pixPreview = $pixPreview[0];

        // Verifica se o saldo do usuário é suficiente para o pagamento
        if (convertAmountToInt($pixPreview->payment->amount) > convertAmountToInt($user->balance())) {
            return response()->json([
                'message' => 'Saldo insuficiente para realizar o pagamento PIX.',
            ], 400);
        }

        // Verifica se o tipo de pagamento é "brcode-payment"
        if ($pixPreview->type != 'brcode-payment') {
            return response()->json([
                'message' => 'Tipo de pagamento não identificado.',
            ], 400);
        }

        // Retorna a pré-visualização do pagamento PIX em formato JSON
        return response()->json([
            'message' => 'Pré-visualização do pagamento PIX gerada com sucesso.',
            'pix_preview' => $pixPreview
        ], 200);
    }

    public function transfer_accounts_preview(Request $request)
    {
        // Validação do request
        $request->validate([
            'amount' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    // Verifica se o valor é um formato monetário válido
                    if (!preg_match('/^\d{1,3}(?:\.\d{3})*(?:,\d{2})?$/', $value)) {
                        $fail('O campo ' . $attribute . ' tem um formato inválido.');
                    }
                }
            ],
            'account' => 'required|string|exists:users,account' // Verifica se a conta existe
        ]);

        // Obtém o usuário autenticado
        $sender = auth('api')->user();

        // Converte o valor para centavos
        $amount = convertAmountToInt($request->amount);
        $account = $request->account;

        // Busca o usuário receptor pela conta
        $receiver = User::where('account', $account)->first();

        // Verifica se o receptor foi encontrado
        if (!$receiver) {
            return response()->json([
                'message' => 'Conta inválida. Verifique se o número está correto e tente novamente.'
            ], 400);
        }

        // Verifica se o saldo do remetente é suficiente
        if ($amount > convertAmountToInt($sender->balance())) {
            return response()->json([
                'message' => 'Saldo insuficiente.'
            ], 400);
        }

        // Retorna a pré-visualização da transferência em formato JSON
        return response()->json([
            'message' => 'Pré-visualização da transferência gerada com sucesso.',
            'receiver' => [
                'id' => $receiver->id,
                'name' => $receiver->name,
                'account' => $receiver->account
            ],
            'amount' => number_format($amount / 100, 2, ',', '.') // Retorna o valor no formato de reais
        ], 200);
    }


    public function payment_pix_key_preview(Request $request)
    {
        // Validação do request
        $request->validate([
            'amount' => ['required', 'string', function ($attribute, $value, $fail) {
                // Verifica se o valor é um formato monetário válido
                if (!preg_match('/^\d{1,3}(?:\.\d{3})*(?:,\d{2})?$/', $value)) {
                    $fail('O campo ' . $attribute . ' tem um formato inválido.');
                }
            }],
            'chave_pix' => 'required|string'
        ]);

        // Converte o valor para centavos
        $amount = convertAmountToInt($request->amount);

        // Formatação da chave PIX (número de celular)
        if (preg_match('/^\d{11}$/', $request->chave_pix)) {
            if (!checkIsCPF($request->chave_pix)) {
                $request->merge([
                    'chave_pix' => '+55' . $request->chave_pix
                ]);
            }
        }

        // Verifica se o usuário já possui uma transação em andamento
        $user = auth('api')->user(); // Obtém o usuário autenticado via token
        $transactionExists = Transaction::where('user_id', Auth::id())
            ->where('operacao', Transaction::OPERACAO_DEBIT)
            ->whereIn('status', [Transaction::STATUS_CRIADO, Transaction::STATUS_PROCESSAMENTO])
            ->exists();
        if ($transactionExists) {
            return response()->json([
                'message' => 'Você já possui uma transação em andamento. Aguarde a conclusão antes de iniciar uma nova.',
            ], 400);
        }

        // Busca a chave PIX no serviço StarkBank
        $starkbankService = new StarbankService();
        $pix = $starkbankService->getDictKey($request->chave_pix);

        // Verifica se houve erro ao buscar a chave PIX
        if (is_array($pix) && isset($pix['error']) && $pix['error']) {
            return response()->json([
                'message' => 'A chave informada não é válida. As chaves aceitas são: e-mails, números de celular no formato internacional (ex: +5511988887777), CPFs, CNPJs ou chaves EVP.',
            ], 400);
        }

        // Verifica se o saldo do usuário é suficiente para a transferência
        if ($amount > convertAmountToInt($user->balance())) {
            return response()->json([
                'message' => 'Saldo insuficiente para efetuar a operação.',
            ], 400);
        }

        // Retorna a pré-visualização da transferência PIX em formato JSON
        return response()->json([
            'message' => 'Pré-visualização da transferência PIX gerada com sucesso.',
            'pix' => $pix,
            'amount' => number_format($amount / 100, 2, ',', '.') // Retorna o valor no formato de reais
        ], 200);
    }
}

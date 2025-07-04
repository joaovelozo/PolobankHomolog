<?php

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MyBank\Payment\Pik\PaymentPixService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Services\MyBank\Account\GetBalanceService;
use App\Services\MyBank\Payment\Pik\ConfirmationPixService;
use Illuminate\Support\Facades\Log;

class PaymentPixKeyController extends Controller
{
    protected $paymentPixService;
    protected $getBalanceService;
    protected $confirmationService;

    public function __construct(PaymentPixService $paymentPixService, GetBalanceService $getBalanceService, ConfirmationPixService $confirmationService)
    {
        $this->paymentPixService = $paymentPixService;
        $this->getBalanceService = $getBalanceService;
        $this->confirmationService = $confirmationService;
    }

    public function index()
    {
        return view('users.payment.transfer');
    }

    public function preview(Request $request)
    {
        Log::info('Entrou no método preview', ['method' => $request->method(), 'all' => $request->all()]);

        if ($request->isMethod('get')) {
            Log::info('Requisição GET detectada, redirecionando para transfer.pix');
            return redirect()->route('transfer.pix');
        }

        $request->validate([
            'amount' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^\d{1,3}(?:\.\d{3})*(?:,\d{2})?$/', $value)) {
                    $fail('O campo ' . $attribute . ' tem um formato inválido.');
                }
            }],
            'tipo_chave_selecionada' => 'required|string',
            'chave_pix' => 'required|string',
        ]);

        $amount = convertAmountToInt($request->amount);
        Log::info('Valor convertido para centavos', ['amount' => $amount]);

        if ($request->tipo_chave_selecionada === '2') {
            $request->merge(['chave_pix' => '+55' . $request->chave_pix]);
            Log::info('Chave PIX do tipo telefone modificada', ['chave_pix' => $request->chave_pix]);
        }

        $transactionExists = Transaction::where('user_id', Auth::id())
            ->where('operacao', Transaction::OPERACAO_DEBIT)
            ->whereIn('status', [Transaction::STATUS_CRIADO, Transaction::STATUS_PROCESSAMENTO])
            ->exists();

        if ($transactionExists) {
            return redirect()->back()->with('error', 'Você já possui uma transação em andamento.');
        }

        $params = [
            'customId' => uniqid('preview_'),
            'idTypeKeyPix' => $request->tipo_chave_selecionada,
            'keyPix' => $request->chave_pix,
            'amount' => $amount,
        ];

        Log::info('Parâmetros montados para o serviço confirmationPix', $params);

        try {
            $response = $this->confirmationService->confirmationPix($params);
               //dd($response);
            Log::info('Resposta do serviço confirmationPix', $response);
        } catch (\Throwable $e) {
            Log::error('Erro ao chamar confirmationPix', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Erro ao obter dados para pré-visualização.');
        }

        return view('users.payment.transfer_preview', [
            'pix' => $response,
            'amount' => $amount,
            'params' => $params,
        ]);
    }


    public function store(Request $request)
    {
        $amount = $request->amount;
        Log::info('Valor convertido com sucesso', ['amount' => $amount]);

        Log::info('Iniciando função store no controller PIX', [
            'user_id' => Auth::id(),
            'payload' => $request->all()
        ]);

        $request->validate([
            'chave_pix' => 'required|string',
            'idTipoChavePIX' => 'required',
            'amount' => 'required',
            'nome' => 'required',
            'cpfcnpj' => 'required',
        ]);

        // Se for telefone, adiciona +55
        if (preg_match('/^\d{11}$/', $request->chave_pix) && !checkIsCPF($request->chave_pix)) {
            $request->merge(['chave_pix' => '+55' . $request->chave_pix]);
            Log::info('Chave PIX identificada como telefone, adicionando +55', [
                'nova_chave_pix' => $request->chave_pix
            ]);
        }

        // Converte o valor para centavos
        $amount = $request->amount;
        Log::info('Valor convertido com sucesso', ['amount' => $amount]);

        // Verifica transação duplicada em menos de 30 segundos
        $lastTransaction = Transaction::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastTransaction && $lastTransaction->created_at->diffInSeconds(now()) < 30) {
            Log::warning('Transação duplicada em menos de 30 segundos detectada.');
            return redirect()->back()->with('error', 'Aguarde alguns segundos para realizar outra transação.');
        }

        // Verifica transação em andamento
        $transactionExists = Transaction::where('user_id', Auth::id())
            ->where('operacao', Transaction::OPERACAO_DEBIT)
            ->whereIn('status', [Transaction::STATUS_CRIADO, Transaction::STATUS_PROCESSAMENTO])
            ->exists();

        if ($transactionExists) {
            Log::warning('Transação em andamento detectada para o usuário: ' . Auth::id());
            return redirect()->back()->with('error', 'Você já possui uma transação em andamento.');
        }

        // Obtém o saldo
        $balance = $this->getBalanceService->GetRealBalance([]);
        Log::info('Saldo recebido da API externa', $balance);

        if (!$balance || ($balance['status'] ?? 'error') !== 'success') {
            Log::error('Erro ao consultar saldo externo ou status inválido.', ['balance' => $balance]);
            return redirect()->back()->with('error', 'Erro ao consultar saldo externo.');
        }

        if ($amount > $balance['balanceAvailable']) {
            Log::warning('Saldo insuficiente.', [
                'amount' => $amount,
                'balanceAvailable' => $balance['balanceAvailable']
            ]);

            return redirect()->route('transfer.pix')->with([
                'message' => 'Seu Saldo insuficiente Para Essa Transação!!',
                'alert-type' => 'error'
            ]);
        }

        DB::beginTransaction();

        try {

            $customId = uniqid('pix_');

            $params = [
                'customId' => $customId,
                'amount' => $amount,
                'nome' => $request->nome,
                'cpfcnpj' => $request->cpfcnpj,
                'idTipoChavePIX' => (int) $request->idTipoChavePIX,
                'chavePIX' => $request->chave_pix,
                'confirmationUrl' => route('pix.webhook'),
                'updateUrl' => route('pix.webhook'),
            ];
      //dd($params);
            Log::info('Parâmetros enviados para PixPayment', $params);

            try {

                $response = $this->paymentPixService->PixPayment($params);
                Log::debug('Resposta da API PixPayment OK', ['resposta' => $response]);
            } catch (\Throwable $e) {
                Log::error('Erro na chamada PixPayment', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                DB::rollBack();
                return redirect()->back()->with('error', 'Erro ao realizar pagamento PIX.');
            }

            $transaction = new Transaction([
                'user_id' => Auth::id(),
                'nome' => $params['nome'],
                'code' => $params['chavePIX'],
                'amount' => $amount / 100,
                'externalId' => $response['id'] ?? null,
                'cpfcnpj' => $params['cpfcnpj'],
                'bank_code' => '000',
                'description' => 'Transferência PIX: #' . $customId,
                'type' => 'transfer',
                'operacao' => Transaction::OPERACAO_DEBIT,
                'method' => Transaction::METHOD_PIX,
            ]);

            $transaction->save();

            DB::commit();
            Log::info('Transação PIX salva com sucesso', ['transaction_id' => $transaction->id]);

            return redirect()->route('payment.pix.view', $transaction->id)
                ->with([
                    'message' => 'Operação realizada com sucesso!',
                    'alert-type' => 'success'
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao processar pagamento PIX', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('transfer.pix')
                ->with('error', 'Erro ao processar o pagamento: ' . $e->getMessage());
        }
    }
}

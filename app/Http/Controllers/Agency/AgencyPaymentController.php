<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pix;
use Auth;
use App\Services\StarbankService;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use App\Services\MyBank\Payment\Pik\PaymentPixService;
use App\Services\MyBank\Account\GetBalanceService;
use App\Services\MyBank\Payment\Pik\ConfirmationPixService;

class AgencyPaymentController extends Controller
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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('agency.pix.index');
    }

    public function pixPreview(Request $request)
    {

        Log::info('Entrou no mpetodo preview',['method' => $request->method(), 'all' => $request->all]);
        if($request->isMethod('get')){
            Log::info('Requisição GET detectada, redirecinando para preview');
            return redirect()->route('payment.agencypreview');
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

        return view('agency.pix.index', [
            'pix' => $response,
            'amount' => $amount,
            'params' => $params,
        ]);
    }



    public function pix()
    {
        return view('agency.payment.pix');
    }


    public function storePix(Request $request)
    {
        $request->validate([
            'codigo_pix' => 'required|string',
            'amount' => ['string', function ($attribute, $value, $fail) {
                // Verifica se o valor é um formato monetário válido
                if (!preg_match('/^\d{1,3}(?:\.\d{3})*(?:,\d{2})?$/', $value)) {
                    $fail('O campo ' . $attribute . ' tem um formato inválido.');
                }
            }],
        ]);

        $transactionExists = Transaction::where('user_id', Auth::id())
            ->where('operacao', Transaction::OPERACAO_DEBIT)
            ->whereIn('status', [Transaction::STATUS_CRIADO, Transaction::STATUS_PROCESSAMENTO])
            ->exists();

        if ($transactionExists) {
            return redirect()->route('agency.payment.pix')->with('error', 'Você já possui uma transação em andamento. Aguarde a conclusão antes de iniciar uma nova.');
        }

        $starkbankService = new StarbankService();
        DB::beginTransaction();
        try {
            $pagamentoPreview = $starkbankService->pagamentoPreview($request->codigo_pix);
            // Se o boleto não for encontrado
            if (!$pagamentoPreview || count($pagamentoPreview) == 0) {
                return redirect()->route('agency.payment.pix')->with('error', 'Pagamento não encontrado ou código inválido.');
            }
            $amount = ($pagamentoPreview[0]->payment->amount > 0) ? $pagamentoPreview[0]->payment->amount : 0;
            if ($amount == 0 && isset($request->amount) && $request->amount > 0) {
                $amount = convertAmountToInt($request->amount);
            }

            if ($amount == 0) {
                return redirect()->route('agency.payment.pix')->with('error', 'Valor a ser pago não informado.');
            }
            if ($amount > convertAmountToInt(Auth::user()->balance())) {
                return redirect()->route('payment.pix')->with('error', 'Saldo insuficiente para efetuar pagamento.');
            }
            // Criação do pagamento
            $pix = $starkbankService->criaQrCodePagamento(
                $request->codigo_pix,
                $amount,
                $pagamentoPreview[0]->payment->taxId,
                'Pagamento PIX para ' . $pagamentoPreview[0]->payment->name,
                Auth::user()
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
            // Retornar mensagem de sucesso
            return redirect()->back()->with('success', 'Pagamento em procesamento!');
        } catch (\StarkBank\Error\InputErrors $e) {
            DB::rollBack();
            // Captura erros específicos da API StarkBank
            $errors = [];
            foreach ($e->errors as $error) {
                $errors[] = "Código: " . $error->errorCode . " - Mensagem: " . $error->errorMessage;
            }
            Log::error('Erro ao processar pagamento via StarkBank: ' . implode(", ", $errors));
            return redirect()->back()->with('error', 'Erro ao processar o pagamento: ' . implode(", ", $errors));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao processar pagamento do boleto: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao processar o pagamento: ' . $e->getMessage());
        }
    }

    public function pix_transfer()
    {
        return view('agency.payment.transfer');
    }

    public function pix_extract()
    {
        $transactions = Transaction::where('user_id', Auth::id())->where('method', Transaction::METHOD_PIX)->where('operacao', Transaction::OPERACAO_DEBIT)->orderBy('created_at', 'DESC')->get();
        return view('agency.payment.extract', compact('transactions'));
    }


    public function store_pix_transfer(Request $request)
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

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StarbankService;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class PaymentPixController extends Controller
{

    public function index()
    {
        return view('users.payment.pix');
    }

    public function extract()
    {
        $transactions = Transaction::where('user_id', Auth::id())->where('method', Transaction::METHOD_PIX)->where('operacao', Transaction::OPERACAO_DEBIT)->orderBy('created_at', 'DESC')->get();
        return view('users.payment.extract', compact('transactions'));
    }


    public function view($id)
    {
        $transaction = Transaction::where('user_id', Auth::id())->where('method', Transaction::METHOD_PIX)->where('operacao', Transaction::OPERACAO_DEBIT)->find($id);
        if (!$transaction) {
            return redirect()->route('payment.pix')->with('error', 'Transação não encontrada.');
        }
        return view('users.payment.pix_view', compact('transaction'));
    }

    public function preview(Request $request)
    {
        $metodo = $request->method();
        // Verifica se é um método específico
        if ($request->isMethod('get')) {
            return redirect()->route('payment.pix');
        }
        $request->validate([
            'codigo_pix' => 'required|string'
        ]);
        $transactionExists = Transaction::where('user_id', Auth::id())
        ->where('operacao', Transaction::OPERACAO_DEBIT)
        ->whereIn('status', [Transaction::STATUS_CRIADO,Transaction::STATUS_PROCESSAMENTO])
        ->exists();
        if ($transactionExists) {
            return redirect()->back()->with('error', 'Você já possui uma transação em andamento. Aguarde a conclusão antes de iniciar uma nova.');
        }
        $starkbankService = new StarbankService();
        $pixPreview = $starkbankService->pagamentoPreview($request->codigo_pix);
        if (isset($pixPreview['error']) && $pixPreview['error']) {
            return redirect()->back()->with('error', 'Pagamento não encontrado ou código inválido.');
        }
        if (!$pixPreview || count($pixPreview) == 0) {
            return redirect()->back()->with('error', 'Pagamento não encontrado ou código inválido.');
        }
        $pixPreview = $pixPreview[0];
        if ($pixPreview->payment->amount > convertAmountToInt(Auth::user()->balance())) {
            return redirect()->back()->with('error', 'Saldo insuficiente para pagar o boleto.');
        }
        if ($pixPreview->type != 'brcode-payment') {
            return redirect()->back()->with('error', 'Tipo de pagamento não identificado.');
        }
        return view('users.payment.pix_preview', compact('pixPreview'));
    }


    public function store(Request $request)
    {
        Log::info('Método store() foi chamado', ['request' => $request->all()]);

        $request->validate([
            'codigo_pix' => 'required|string',
            'amount' => ['string', function ($attribute, $value, $fail) {
                // Verifica se o valor é um formato monetário válido
                if (!preg_match('/^\d{1,3}(?:\.\d{3})*(?:,\d{2})?$/', $value)) {
                    $fail('O campo ' . $attribute . ' tem um formato inválido.');
                }
            }],
        ]);

        // Verificar se há uma transação recente (menos de 1 minuto)
        $lastTransaction = Transaction::where('user_id', Auth::id())
        ->orderBy('created_at', 'desc')
        ->first();

        if ($lastTransaction && $lastTransaction->created_at->diffInSeconds(now()) < 30) {
            return redirect()->back()->with('error', 'Aguarde alguns segundos para realizar outra transação.');
        }

        $transactionExists = Transaction::where('user_id', Auth::id())
        ->where('operacao', Transaction::OPERACAO_DEBIT)
        ->whereIn('status', [Transaction::STATUS_CRIADO,Transaction::STATUS_PROCESSAMENTO])
        ->exists();

        if ($transactionExists) {
            return redirect()->route('payment.pix')->with('error', 'Você já possui uma transação em andamento. Aguarde a conclusão antes de iniciar uma nova.');
        }
        $starkbankService = new StarbankService();
        DB::beginTransaction();
        try {
            $pagamentoPreview = $starkbankService->pagamentoPreview($request->codigo_pix);
            // Se o boleto não for encontrado
            if (!$pagamentoPreview || count($pagamentoPreview) == 0) {
                return redirect()->route('payment.pix')->with('error', 'Pagamento não encontrado ou código inválido.');
            }
            $amount = ($pagamentoPreview[0]->payment->amount > 0) ? $pagamentoPreview[0]->payment->amount : 0;
            if ($amount == 0 && isset($request->amount) && $request->amount > 0) {
                $amount = convertAmountToInt($request->amount);
            }
            if ($amount == 0) {
                return redirect()->route('payment.pix')->with('error', 'Valor a ser pago não informado.');
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
            $transaction = new Transaction([
                'user_id' => Auth::id(),
                'name' => $pagamentoPreview[0]->payment->name,
                'code' => $request->codigo_pix,
                'amount' => $amount / 100,
                'status' => Transaction::STATUS_CRIADO,
                'externalId' => $pix[0]->id,
                'document_number' =>  $pagamentoPreview[0]->payment->taxId,
                'bank_code' =>   $pagamentoPreview[0]->payment->bankCode,
                'description' => 'Pagamento de pix: #'.$pix[0]->id,
                'type' => 'transfer',
                'operacao' => Transaction::OPERACAO_DEBIT,
                'method' => Transaction::METHOD_PIX,
                'fee' => $pix[0]->fee/ 100,
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
            Log::error('Erro ao processar pagamento do pix: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao processar o pagamento: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StarbankService;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class PaymentBoletoController extends Controller
{


    public function index()
    {
        return view('users.payment.boleto');
    }

    public function extract()
    {
        $transactions = Transaction::where('user_id', Auth::id())->where('method', Transaction::METHOD_BOLETO)->where('operacao', Transaction::OPERACAO_DEBIT)->orderBy('created_at', 'DESC')->get();
        return view('users.payment.boleto_extract', compact('transactions'));
    }
    public function view($id)
    {
        $transaction = Transaction::where('user_id', Auth::id())->where('method', Transaction::METHOD_BOLETO)->where('operacao', Transaction::OPERACAO_DEBIT)->find($id);
        if (!$transaction) {
            return redirect()->route('payment.boleto')->with('error', 'Boleto não encontrado.');
        }
        return view('users.payment.boleto_view', compact('transaction'));
    }

    public function preview(Request $request)
    {
        $metodo = $request->method();
        // Verifica se é um método específico
        if ($request->isMethod('get')) {
            return redirect()->route('payment.boleto');
        }
        $request->validate([
            'codigo_barra' => 'required|string'
        ]);
        $transactionExists = Transaction::where('user_id', Auth::id())
        ->where('operacao', Transaction::OPERACAO_DEBIT)
        ->whereIn('status', [Transaction::STATUS_CRIADO,Transaction::STATUS_PROCESSAMENTO])
        ->exists();
        if ($transactionExists) {
            return redirect()->back()->with('error', 'Você já possui uma transação em andamento. Aguarde a conclusão antes de iniciar uma nova.');
        }
        $starkbankService = new StarbankService();
        $boletoPreview = $starkbankService->pagamentoPreview($request->codigo_barra);
        if (isset($boletoPreview['error']) && $boletoPreview['error']) {
            return redirect()->back()->with('error', 'Boleto não encontrado ou código de barras inválido.');
        }
        if (!$boletoPreview || count($boletoPreview) == 0) {
            return redirect()->back()->with('error', 'Boleto não encontrado ou código de barras inválido.');
        }
        $boletoPreview = $boletoPreview[0];
        if ($boletoPreview->payment->amount > convertAmountToInt(Auth::user()->balance())) {
            return redirect()->back()->with('error', 'Saldo insuficiente para pagar o boleto.');
        }
        return view('users.payment.boleto_preview', compact('boletoPreview'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo_barra' => 'required|string'
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
            return redirect()->back()->with('error', 'Você já possui uma transação em andamento. Aguarde a conclusão antes de iniciar uma nova.');
        }
        $starkbankService = new StarbankService();
        DB::beginTransaction();
        try {
            $boletoPreview = $starkbankService->pagamentoPreview($request->codigo_barra);
            // Se o boleto não for encontrado
            if (!$boletoPreview || count($boletoPreview) == 0) {
                throw new \Exception("Boleto não encontrado ou código de barras inválido.");
            }
            if ($boletoPreview[0]->payment->amount > convertAmountToInt(Auth::user()->balance())) {
                return redirect()->back()->with('error', 'Saldo insuficiente para pagar o boleto.');
            }
            // Criação do pagamento
            $boleto = $starkbankService->criaBoletoPagamento(
                $request->codigo_barra,
                $boletoPreview[0]->payment->taxId,
                'Pagamento de boleto',
                Auth::user()
            );
            // Salvar a transação na base de dados
            $transaction = new Transaction([
                'user_id' => Auth::id(),
                'name' => $boletoPreview[0]->payment->name,
                'code' => $request->codigo_barra,
                'amount' => $boletoPreview[0]->payment->amount / 100, 
                'status' => Transaction::STATUS_CRIADO, 
                'externalId' => $boleto[0]->id,
                'document_number' =>  $boletoPreview[0]->payment->taxId,
                'bank_code' =>   substr($boletoPreview[0]->payment->barCode, 0, 3),
                'description' => 'Pagamento de boleto: #'.$boleto[0]->id,
                'type' => 'transfer',
                'operacao' => Transaction::OPERACAO_DEBIT,
                'method' => Transaction::METHOD_BOLETO,
                'fee' => $boleto[0]->fee/ 100,
            ]);

            
            $transaction->save();
            DB::commit();
            // Retornar mensagem de sucesso
            return redirect()->route('payment.boleto.view', $transaction->id)->with('success', 'Pagamento em procesamento!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao processar pagamento do boleto: ' . $e->getMessage());
            // Retornar mensagem de erro
            return redirect()->route('payment.boleto')->with('error', 'Erro ao processar o pagamento: ' . $e->getMessage());
        }
    }

   
}

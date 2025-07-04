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

class AgencyPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('agency.pix.index');
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
        $request->validate([
            'amount' => ['required', 'string', function ($attribute, $value, $fail) {
                // Verifica se o valor é um formato monetário válido
                if (!preg_match('/^\d{1,3}(?:\.\d{3})*(?:,\d{2})?$/', $value)) {
                    $fail('O campo ' . $attribute . ' tem um formato inválido.');
                }
            }],
            'tipo_chave_selecionada' => 'required|string',
            'chave_pix' => 'required|string'
        ]);
        // Formatação da chave PIX (número de celular)
        if ($request->tipo_chave_selecionada=='telefone') {
            $request->merge([
                'chave_pix' => '+55' . $request->chave_pix
            ]);
        }
        $transactionExists = Transaction::where('user_id', Auth::id())
            ->where('operacao', Transaction::OPERACAO_DEBIT)
            ->whereIn('status', [Transaction::STATUS_CRIADO, Transaction::STATUS_PROCESSAMENTO])
            ->exists();
        if ($transactionExists) {
            return redirect()->back()->with('error', 'Você já possui uma transação em andamento. Aguarde a conclusão antes de iniciar uma nova.');
        }
        $amount = convertAmountToInt($request->amount);
        $starkbankService = new StarbankService();
        DB::beginTransaction();
        try {
            $pix = $starkbankService->getDictKey($request->chave_pix);
            // Se o boleto não for encontrado
            if (!$pix) {
                throw new \Exception("Chave pix não encontrada.");
            }
            if ($amount > convertAmountToInt(Auth::user()->balance())) {
                return redirect()->back()->with('error', 'Saldo insuficiente para pagar.');
            }

            // Criação do pagamento
            $transferencia = $starkbankService->tranferencia(
                $amount,
                $pix->ispb,
                $pix->branchCode,
                $pix->accountNumber,
                $pix->taxId,
                $pix->name,
                Auth::user()
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

            // Retornar mensagem de sucesso
            return redirect()->back()->with('success', 'Pagamento em procesamento!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao processar pagamento do pix: ' . $e->getMessage());

            // Retornar mensagem de erro
            return redirect()->back()->with('error', 'Erro ao processar o pagamento: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StarbankService;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class BoletoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('users.boleto.index');
    }

    public function create()
    {
        return view('users.boleto.create');
    }
    public function extract()
    {
        $transactions = Transaction::where('user_id', Auth::id())->where('method', Transaction::METHOD_BOLETO)->where('operacao', Transaction::OPERACAO_CREDIT)->orderBy('created_at', 'DESC')->get();
        return view('users.boleto.extract', compact('transactions'));
    }

    public function store(Request $request)
    {
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
            $user = Auth::user();
            if (!isValidCPF($user->document)) {
                return redirect()->back()->with('error', 'O CPF associado à sua conta é inválido. Por favor, atualize suas informações de CPF em "Minha Conta" para corrigir o problema.');
            }
            $cep = str_replace('.', '', $user->zipcode);
            if (!isValidCEP($cep)) {
                return redirect()->back()->with('error', 'O CEP associado à sua conta é inválido. Por favor, atualize seu CEP em "Minha Conta" para corrigir o problema.');
            }
            $amount = convertAmountToInt($request->amount);
            $name = $user->name;
            $taxId = $user->document;
            $rua = $user->address;
            $complemento = $user->number . ' - ' . $user->complement;
            $bairro = $user->neighborhood;
            $cidade = $user->city;
            $estado = $user->state;
            $cep = $cep;
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
                Auth::user()
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
            // Retornar mensagem de sucesso
            return redirect()->route('boleto.view', $transaction->id)->with('success', 'Boleto gerado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao gerar boleto: ' . $e->getMessage());
            // Retornar mensagem de erro
            return redirect()->back()->with('error', 'Erro ao gerar boleto: ' . $e->getMessage());
        }
    }

    public function view($id)
    {
        $transaction = Transaction::where('user_id', Auth::id())->find($id);
        if (!$transaction) {
            return redirect()->back()->with('error', 'Boleto não encontrado');
        }
        $starkbankService = new StarbankService();
        $boleto = $starkbankService->buscaBoletoRecebimento($transaction->externalId);
        return view('users.boleto.view', compact('boleto'));
    }

    public function download($id)
    {
        try {
            $starkbankService = new StarbankService();
            // Gera o PDF do boleto usando a API da StarkBank
            $pdf = $starkbankService->buscaBoletoRecebimentoPDF($id);
            // Nome do arquivo PDF
            $fileName = 'boleto-' . $id . '.pdf';
            // Caminho temporário para salvar o PDF
            $tempPath = storage_path('app/public/' . $fileName);
            // Cria o arquivo e escreve o conteúdo do PDF nele
            $fp = fopen($tempPath, 'w');
            fwrite($fp, $pdf);
            fclose($fp);
            // Retorna o arquivo PDF como resposta para download
            return response()->download($tempPath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Erro ao gerar o PDF do boleto: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao baixar o PDF do boleto.');
        }
    }
}

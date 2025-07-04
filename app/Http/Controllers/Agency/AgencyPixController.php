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

class AgencyPixController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function qrcode()
    {
        return view('agency.pix.qrcode');
    }

    public function pix_qrcode_extract()
    {
        $transactions = Transaction::where('user_id', Auth::id())->where('method', Transaction::METHOD_PIX)->where('operacao', Transaction::OPERACAO_CREDIT)->orderBy('created_at', 'DESC')->get();
        return view('agency.pix.extract', compact('transactions'));
    }

    public function create_qrcode(Request $request)
    {
        // Validar o request
        $request->validate([
            'amount' => ['required', 'string', function ($attribute, $value, $fail) {
                // Verifica se o valor é um formato monetário válido
                if (!preg_match('/^\d{1,3}(?:\.\d{3})*(?:,\d{2})?$/', $value)) {
                    $fail('O campo ' . $attribute . ' tem um formato inválido.');
                }
            }],
        ]);

        $starkbankService = new StarbankService();
        $amount = convertAmountToInt($request->amount);
        DB::beginTransaction();
        try {
            // Criar o QR Code
            $qrcode = $starkbankService->criaQrCodeRecebimento($amount, Auth::user());
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

            DB::commit();
            return redirect()->route('agency.qrcode.view', $transaction->id)->with('success', 'QR Code criado com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar QR Code: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao criar QR Code: ' . $e->getMessage());
        }
    }

    public function view_qrcode($id)
    {
        $transaction = Transaction::where('user_id', Auth::id())->find($id);
        if (!$transaction) {
            return redirect()->back()->with('error', 'QR Code não encontrado');
        }
        $starkbankService = new StarbankService();

        $brcode = $starkbankService->buscaQrCodeRecebimento($transaction->externalId, Auth::user());

        return view('agency.pix.view_qrcode', compact('brcode'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.pix.create');
    }
}

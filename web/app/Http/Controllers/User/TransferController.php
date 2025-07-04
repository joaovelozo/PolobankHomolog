<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Agency;
use App\Models\OpenContract;
use App\Models\User;
use App\Models\Card;
use App\Models\Split;
use App\Models\UserServices;
use App\Services\MyBank\Payment\Internal\InternalTransferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\StarbankService;
use App\Services\TelemedicinaService;
use Auth as GlobalAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Str;
use Illuminate\Validation\Rules;

class TransferController extends Controller
{
    protected $internalTransferService;
    public function __construct(InternalTransferService $internalTransferService)
    {
        $this->internalTransferService = $internalTransferService;
    }


    public function index()
    {
        return view('users.transfer.index');
    }

    public function accounts()
    {
        return view('users.transfer.accounts');
    }

    public function extract()
    {
        $transactions = Transaction::where('user_id',  Auth::id())->where('operacao', Transaction::OPERACAO_DEBIT)->where('type',  Transaction::TYPE_TRANSFER)->where('method', Transaction::METHOD_TRANSFER)->orderBy('created_at', 'DESC')->get();
        return view('users.transfer.extract', compact('transactions'));
    }

    public function preview(Request $request)
    {
        $metodo = $request->method();
        // Verifica se é um método específico
        if ($request->isMethod('get')) {
            return redirect()->route('transfer.accounts');
        }
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
            'documentNumber' => 'required',
            'accountNumber' => 'required'
        ]);
        $sender = Auth::user();
        $amount = convertAmountToInt($request->amount);
        $account = $request->accountNumber;
        $receiver = User::where('accountNumber', $account)->first();
        if (!$receiver) {
            return redirect()->back()->with('error', 'Conta inválida. Verifique se o número esta correto e tente novamente.');
        }
        if ($amount > convertAmountToInt($sender->balance())) {
            return redirect()->back()->with('error', 'Saldo insuficiente.');
        }
        return view('users.transfer.preview', compact('receiver', 'amount'));
    }

    public function store(Request $request)
    {
        Log::info('Iniciando função store no controller Tranferência Interna', [
            'user_id' => Auth::id(),
            'payload' => $request->all()
        ]);

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
        ]);
        $amount = convertAmountToInt($request->amount);
        $account = $request->accountNumber;
        $sender = Auth::user();
        $receiver = User::where('accountNumber', $account)->first();
        try {
            if (!$receiver) {
                return redirect()->route('transfer.accounts')->with('error', 'Conta inválida. Verifique se o número esta correto e tente novamente.');
            }
            if ($amount > convertAmountToInt($sender->balance())) {
                return redirect()->route('transfer.accounts')->with('error', 'Saldo insuficiente.');
            }
            DB::beginTransaction();


            $saida = new Transaction([
                'user_id' => Auth::id(),
                'code' => $account,
                'amount' => $amount / 100,
                'status' => Transaction::STATUS_SUCESSO,
                'externalId' =>  null,
                'name' => $receiver->name,
                'status' => Transaction::STATUS_SUCESSO,
                'documentNumber' =>  $receiver->document,
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
                'documentNumber' =>  $sender->document,
                'bank_code' =>  'Polocal Bank',
                'account_number' =>  $sender->account,
                'description' => 'Fez recebeu uma transferencia de: #' . $sender->id,
                'type' => Transaction::TYPE_TRANSFER,
                'operacao' => Transaction::OPERACAO_CREDIT,
                'method' => Transaction::TYPE_TRANSFER,
            ]);


            $entrada->save();
            // Chamada do serviço externo de transferência interna
            $this->internalTransferService->inTransfer([
                'accountNumber' => $receiver->account,
                'documentNumber' => $receiver->document,
                'amount' => $amount
            ]);

            DB::commit();

            return redirect()->route('transfer.accounts')->with('success', 'Transferência concluída com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao transferir: ' . $e->getMessage());
            return redirect()->route('transfer.accounts')->with('error', 'Erro ao efetuar transferência: ' . $e->getMessage());
        }
    }
}

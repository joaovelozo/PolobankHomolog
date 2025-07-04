<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Agency;
use App\Models\OpenContract;
use App\Models\User;
use App\Models\Card;
use App\Models\Split;
use App\Models\UserServices;
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

class AgencyTransferController extends Controller
{


    public function transferIndex()
    {
        return view('agency.transfer.index');
    }

    public function transfer_accounts()
    {
        return view('agency.transfer.accounts');
    }

    public function transfer_extract()
    {
        $transactions = Transaction::where('user_id', Auth::id())->where('method', Transaction::METHOD_TRANSFER)->orderBy('created_at', 'DESC')->get();
        return view('agency.transfer.extract', compact('transactions'));
    }

    public function transfer_accounts_store(Request $request)
    {

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
        $account = $request->account;
        $sender = Auth::user();
        $receiver = User::where('account', $account)->first();
        try {
            if (!$receiver) {
                return redirect()->back()->with('error', 'Conta inválida. Verifique se o número esta correto e tente novamente.');
            }
            if ($amount > convertAmountToInt($sender->balance())) {
                return redirect()->back()->with('error', 'Saldo insuficiente.');
            }

            DB::beginTransaction();
            $saida = new Transaction([
                'user_id' => Auth::id(),
                'code' => $account,
                'amount' => $amount / 100, 
                'status' => Transaction::STATUS_SUCESSO, 
                'externalId' =>  null,
                'name' => $receiver->name,
                'amount' => $amount / 100, 
                'status' => Transaction::STATUS_SUCESSO, 
                'document_number' =>  $receiver->document,
                'bank_code' =>  'Polocal Bank',
                'branch_code' =>  $receiver->agency->number,
                'account_number' =>  $receiver->account,
                'description' => 'Fez uma transferencia para: #'.$receiver->id,
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
                'amount' => $amount / 100, 
                'status' => Transaction::STATUS_SUCESSO, 
                'document_number' =>  $sender->document,
                'bank_code' =>  'Polocal Bank',
                'branch_code' =>  $sender->agency->number,
                'account_number' =>  $sender->account,
                'description' => 'Fez recebeu uma transferencia de: #'.$sender->id,
                'type' => Transaction::TYPE_TRANSFER,
                'operacao' => Transaction::OPERACAO_CREDIT,
                'method' => Transaction::TYPE_TRANSFER,
            ]);


            $entrada->save();
            DB::commit();
            return redirect()->back()->with('success', 'Transferência concluída com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao gerar boleto: ' . $e->getMessage());

            // Retornar mensagem de erro
            return redirect()->back()->with('error', 'Erro ao gerar boleto: ' . $e->getMessage());
        }
    }
}

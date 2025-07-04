<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Type;
use App\Models\UserInvestment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;

class UserInvestmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Recuperar o usuário atualmente autenticado
        $user = Auth::user();

        // Calcular o valor total dos investimentos do usuário
        $totalInvested = UserInvestment::where('user_id', $user->id)->sum('amount');

        // Recuperar os investimentos do usuário atual
        $ivs = $user->investments;

        $show = Investment::all();

        return view('users.investment.index', compact('ivs', 'totalInvested', 'show'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ivs = Investment::all();
        return view('users.investment.create', compact('ivs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar os dados recebidos do formulário
        $request->validate([
            'amount' => ['required', 'string', function ($attribute, $value, $fail) {
                // Verifica se o valor é um formato monetário válido
                if (!preg_match('/^\d{1,3}(?:\.\d{3})*(?:,\d{2})?$/', $value)) {
                    $fail('O campo ' . $attribute . ' tem um formato inválido.');
                }
            }],
            'investment_id' => 'required|exists:investments,id',
        ]);
        $amount = convertAmountToInt($request->amount);
        if ($amount > convertAmountToInt(Auth::user()->balance())) {
            return redirect()->back()->with('error', 'Saldo insuficiente para fazer investimento.');
        }
        $amount = $amount / 100;
        // Recuperar o usuário atualmente autenticado
        $user = Auth::user();
        // Recuperar o usuário para o qual o saldo será transferido (containvestimento@user.com)
        $receiver = User::where('email', 'containvestimento@polocalbank.com.br')->first();
        $selectedInvestment = Investment::findOrFail($request->investment_id);
        if ($selectedInvestment->amount > $amount){
            return redirect()->back()->with('error', 'Investimento minimo é: R$'.$selectedInvestment->amount);
        }
        // Definir a data de início como a data atual
        $startDate = Carbon::now();

        // Definir a data de término adicionando o período do investimento
        // Suponha que o campo "term" em "Investments" armazene o número de meses de duração
        $endDate = Carbon::now()->addMonths($selectedInvestment->term);

        // Iniciar uma transação
        DB::beginTransaction();

        try {
            // Criar uma nova entrada na tabela userinvestments
            $investment = new UserInvestment();
            $investment->user_id = $user->id;
            $investment->investment_id = $request->investment_id;
            $investment->type_id = Type::where('name', 'Investimentos')->first()->id;
            $investment->amount = $amount;
            $investment->start_date = $startDate;
            $investment->end_date = $endDate;
            $investment->save();

            // Debitar o saldo do usuário atual (enviando para containvestimento@user.com)
            $user->balance -= $amount;
            $user->save();

            // Creditar o saldo do usuário de destino
            $receiver->balance += $amount;
            $receiver->save();

            // Criar uma nova entrada na tabela transactions para registrar a transferência
            $transaction = new Transaction([
                'sender_id' => $user->id,
                'receiver_id' =>  $receiver->id,
                'type_id' => Type::where('name', 'Investimentos')->first()->id, // Defina o ID do tipo de transação apropriado
                'amount' => $amount / 100, // Convertendo centavos para reais
                'status' => Transaction::STATUS_SUCESSO, // Supondo que 1 seja "Approved", você pode ajustar conforme necessário
                'id_transaction' => null,
                'type_transaction' => Transaction::TRANSFERENCIA,
                'operacao' => Transaction::SAIDA,
                'name' => 'Investimentos',
            ]);
            $transaction->save();

            // Confirmar a transação
            DB::commit();

            // Redirecionar de volta para a página de índice de investimentos com uma mensagem de sucesso
            return redirect()->route('userinvestment.index')->with('success', 'Investimento realizado com sucesso!');

        } catch (\Exception $e) {
            // Em caso de erro, desfazer a transação
            DB::rollBack();

            // Redirecionar de volta para a página de índice de investimentos com uma mensagem de erro
            return redirect()->route('userinvestment.index')->with('error', 'Ocorreu um erro ao processar o investimento. Por favor, tente novamente mais tarde.');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

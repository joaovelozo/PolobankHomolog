<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Investment;
use App\Models\User;
use App\Models\Type;
use App\Models\UserInvestment;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;
use App\Models\Transaction;

class AdminInvestmentController extends Controller
{

    public function search(Request $request)
    {
        $search = $request->input('q'); // O termo de busca

        // Busca usuários que contenham o termo de busca no nome
        $users = User::where('name', 'LIKE', "%{$search}%")->limit(20)->get();

        return response()->json($users);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Exibe a lista de investimentos cadastrados
        $ivs = UserInvestment::with(['user', 'investment', 'type'])->get();
        return view('admin.userinvestment.index', compact('ivs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Exibe o formulário de criação de investimentos
        $investments = Investment::all();
        return view('admin.userinvestment.create', compact('investments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Valida os dados enviados
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'investment_id' => 'required|exists:investments,id',
            'amount' => ['required', 'string', function ($attribute, $value, $fail) {
                // Verifica se o valor é um formato monetário válido
                if (!preg_match('/^\d{1,3}(?:\.\d{3})*(?:,\d{2})?$/', $value)) {
                    $fail('O campo ' . $attribute . ' tem um formato inválido.');
                }
            }],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        $user = User::find($request->user_id);
        $amount = convertAmountToInt($request->amount);
        if (!$user) {
            return redirect()->back()->with('error', 'Cliente não encontrado.');
        }
        //if ($amount > converterBalanceToInt($user->balance)) {
        //    return redirect()->back()->with('error', 'Saldo insuficiente para investir.');
        //}
        // Buscar o investimento para obter a taxa
        $investment = Investment::findOrFail($request->investment_id);
        if (!$investment) {
            return redirect()->back()->with('error', 'Investimento não encontrado.');
        }
        $amount = $amount / 100;
        if ($investment->amount > $amount) {
            return redirect()->back()->with('error', 'Investimento minimo é: R$' . $investment->amount);
        }
        // Recuperar o usuário para o qual o saldo será transferido (containvestimento@user.com)
        $receiver = User::where('account', '0977793918')->first();
        // Definir a data de início como a data atual
        $startDate = ($request->start_date?$request->start_date:Carbon::now());

        // Definir a data de término adicionando o período do investimento
        // Suponha que o campo "term" em "Investments" armazene o número de meses de duração
        $endDate = ($request->end_date?$request->end_date:Carbon::now()->addMonths($investment->term));
        // Iniciar uma transação
        DB::beginTransaction();

        try {
            // Criar uma nova entrada na tabela userinvestments
            $investment = new UserInvestment();
            $investment->user_id = $user->id;
            $investment->investment_id = $request->investment_id;
            $investment->type_id = Type::where('name', 'Investimento')->first()->id;
            $investment->amount = $amount;
            $investment->start_date = $startDate;
            $investment->end_date = $endDate;
            $investment->save();

            // Debitar o saldo do usuário atual (enviando para containvestimento@user.com)
            //$user->balance -= $amount;
            //$user->save();

            // Creditar o saldo do usuário de destino
            $receiver->balance += $amount;
            $receiver->save();

            // Criar uma nova entrada na tabela transactions para registrar a transferência
            $transaction = new Transaction([
                'sender_id' => $user->id,
                'receiver_id' =>  $receiver->id,
                'type_id' => Type::where('name', 'Investimento')->first()->id, // Defina o ID do tipo de transação apropriado
                'amount' => $amount / 100, // Convertendo centavos para reais
                'status' => Transaction::STATUS_SUCESSO, // Supondo que 1 seja "Approved", você pode ajustar conforme necessário
                'id_transaction' => null,
                'type_transaction' => Transaction::INVESTIMENTO,
                'operacao' => Transaction::SAIDA,
                'name' => $investment->title,
            ]);
            $transaction->save();

            // Confirmar a transação
            DB::commit();

            // Redirecionar de volta para a página de índice de investimentos com uma mensagem de sucesso
            return redirect()->back()->with('success', 'Investimento realizado com sucesso!');
        } catch (\Exception $e) {
            // Em caso de erro, desfazer a transação
            DB::rollBack();

            // Redirecionar de volta para a página de índice de investimentos com uma mensagem de erro
            return redirect()->back()->with('error', 'Ocorreu um erro ao processar o investimento. Por favor, tente novamente mais tarde.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Exibe os detalhes de um investimento específico
        $investment = UserInvestment::with(['user', 'investment'])->findOrFail($id);
        return view('admin.userinvestment.show', compact('investment'));
    }


    public function redemption($id)
    {
        // Exibe os detalhes de um investimento específico
        $investment = UserInvestment::with(['user', 'investment'])->findOrFail($id);
        return view('admin.userinvestment.redemption', compact('investment'));
    }
    public function redemption_store(Request $request,$id)
    {
        // Valida os dados enviados
        $request->validate([
            'end_date' => 'required|date',
        ]);
        $investment = UserInvestment::find($request->id);
        if (!$investment) {
            return redirect()->back()->with('error', 'Investimento não encontrado.');
        }
        $retorno = calculateDailyReturn($investment->amount, $investment->start_date, $investment->investment->performance, $investment->investment->tax);
        DB::beginTransaction();
        try {
            // Criar uma nova entrada na tabela userinvestments
            $investment->redemption_date = $request->end_date;
            $investment->calculated_return = $retorno['valor_atual'];
            $investment->save();
            // Confirmar a transação
            DB::commit();
            // Redirecionar de volta para a página de índice de investimentos com uma mensagem de sucesso
            return redirect()->back()->with('success', 'Investimento resgatado com sucesso!');
        } catch (\Exception $e) {
            // Em caso de erro, desfazer a transação
            DB::rollBack();
            // Redirecionar de volta para a página de índice de investimentos com uma mensagem de erro
            return redirect()->back()->with('error', 'Ocorreu um erro ao resgatar o investimento. Por favor, tente novamente mais tarde.');
        }
       

    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Iniciar uma transação
        DB::beginTransaction();
        try {
            // Remove o investimento do usuário
            $userInvestment = UserInvestment::findOrFail($id);
            //$user = $userInvestment->user;
            //$user->balance += $userInvestment->amount;
           // $user->save();
            $userInvestment->delete();
            DB::commit();
            return redirect()->route('useradmininvestment.index')
                ->with('success', 'Investimento removido com sucesso!');
        } catch (\Exception $e) {
            // Em caso de erro, desfazer a transação
            DB::rollBack();

            // Redirecionar de volta para a página de índice de investimentos com uma mensagem de erro
            return redirect()->back()->with('error', 'Ocorreu um erro ao processar o investimento. Por favor, tente novamente mais tarde.');
        }
    }
}

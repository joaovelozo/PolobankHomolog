<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Type;
use Auth;
use Illuminate\Support\Facades\Log;

class AgencyDebitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Obtém o ID da agência do usuário autenticado
            $agencyId = auth()->user()->agency_id;

            // Obtém todas as transações do tipo "Débito em Conta" associadas à agência do usuário autenticado
            $debitTransactions = Transaction::whereHas('sender', function ($query) use ($agencyId) {
                $query->where('agency_id', $agencyId);
            })->get();

            // Inicializa arrays para armazenar transações com saldo suficiente e saldo insuficiente
            $successfulTransactions = [];
            $insufficientBalanceTransactions = [];

            foreach ($debitTransactions as $transaction) {
                // Verifica se a transação foi efetuada com sucesso ou se foi devido a saldo insuficiente
                if ($transaction->status === 'success') {
                    $successfulTransactions[] = $transaction;
                } else {
                    $insufficientBalanceTransactions[] = $transaction;
                }
            }

            return view('agency.debit.index', compact('successfulTransactions', 'insufficientBalanceTransactions'));
        } catch (\Exception $e) {
            Log::error('Erro ao listar transações de débito: ' . $e->getMessage());
            Log::error($e);

            return redirect()->back();
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

    public function search(Request $request)
    {
        $search = $request->input('q'); // O termo de busca
        $agencyId = auth()->user()->agency_id;
        $users = User::where('agency_id', $agencyId)->where('name', 'LIKE', "%{$search}%")->limit(20)->get();
        return response()->json($users);
    }

    public function debit()
    {
        // Supondo que você tenha acesso ao ID da agência logada
        $agencyId = auth()->user()->agency_id;
        $types = Type::all();
        return view('agency.debit.add', compact('types'));
    }

    // Função de débito
    public function DebitStore(Request $request)
    {
        try {
            // Validação dos dados de entrada
            $request->validate([
                'user_id' => 'nullable',
                'amount' => ['required', 'string', function ($attribute, $value, $fail) {
                    // Verifica se o valor é um formato monetário válido
                    if (!preg_match('/^\d{1,3}(?:\.\d{3})*(?:,\d{2})?$/', $value)) {
                        $fail('O campo ' . $attribute . ' tem um formato inválido.');
                    }
                }],
            ]);
            // ID do gerente logado
            $managerId = Auth::user()->id;
            $amount = convertAmountToInt($request->amount);
            $amount = $amount / 100;
            // Obtém o gerente logado
            $manager = Auth::user();

            // Verifica se pelo menos um cliente foi selecionado
            if ($request->has('user_id')) {
                $userIds = $request->input('user_id');

                // Se a opção "Todos os clientes" foi selecionada
                if ($userIds === 'all') {
                    // Obtém todos os usuários associados à agência do gerente
                    $users = User::where('agency_id', $manager->agency_id)->get();

                    foreach ($users as $user) {
                        // Verifica se o saldo do usuário é suficiente para debitar
                        if ($user->balance >= $amount) {
                            // Atualiza o saldo do usuário subtraindo o valor
                            $newBalance = $user->balance - $amount;
                            $user->balance = $newBalance;

                            // Salva o usuário
                            $user->save();

                            // Cria uma nova transação para registrar o débito
                            Transaction::create([
                                'sender_id' => $user->id,
                                'receiver_id' => $managerId,
                                'name' => $manager->agency->name, // Nome da agência do gerente
                                'amount' => $amount,
                                'type_id' => $request->input('type_id'),
                                'status' => Transaction::STATUS_SUCESSO, // Supondo que 1 seja "Approved", você pode ajustar conforme necessário
                                'type_transaction' => Transaction::TRANSFERENCIA,
                                'operacao' => Transaction::SAIDA
                                // Adicione outros campos necessários
                            ]);
                        }
                    }

                    $notification = [
                        'message' => 'Saldo debitado com sucesso!',
                        'alert-type' => 'success'
                    ];

                    return redirect()->back()->with($notification);
                } else {
                    // Se apenas um usuário foi selecionado, proceda com a lógica existente
                    $user = User::findOrFail($userIds);

                    // Verifica se o saldo do usuário é suficiente para debitar
                    if ($user->balance >= $amount) {
                        // Atualiza o saldo do usuário subtraindo o valor
                        $newBalance = $user->balance - $amount;
                        $user->balance = $newBalance;
                        // Salva o usuário
                        $user->save();

                        Transaction::create([
                            'sender_id' => $user->id,
                            'receiver_id' => $managerId,
                            'name' => $manager->agency->name, // Nome da agência do gerente
                            'amount' => $amount,
                            'type_id' => $request->input('type_id'),
                            'status' => Transaction::STATUS_SUCESSO, // Supondo que 1 seja "Approved", você pode ajustar conforme necessário
                            'type_transaction' => Transaction::TRANSFERENCIA,
                            'operacao' => Transaction::SAIDA
                            // Adicione outros campos necessários
                        ]);

                        $notification = [
                            'message' => 'Saldo debitado com sucesso!',
                            'alert-type' => 'success'
                        ];

                        return redirect()->back()->with($notification);
                    } else {
                        $notification = [
                            'message' => 'Saldo insuficiente para o débito!',
                            'alert-type' => 'danger'
                        ];
                        return redirect()->back()->with($notification);
                    }
                }
            } else {
                // Se nenhum cliente foi selecionado, apenas redirecione sem fazer qualquer operação
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Log::error('Erro ao debitar saldo: ' . $e->getMessage());
            Log::error($e);

            $notification = [
                'message' => 'Ocorreu um erro ao debitar o saldo!',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification);
        }
    }
}

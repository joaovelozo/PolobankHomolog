<?php

namespace App\Http\Controllers\Api\Investments;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use Illuminate\Http\Request;
use Auth;
use App\Models\UserInvestment;
use App\Models\User;
use App\Models\Type;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InvestmentController extends Controller
{
    public function getInvestment()
    {
        try {
            $ivs = Investment::all()->map(function ($ivs) {
                return [
                    'id' => $ivs->id,
                    'term' => $ivs->term,
                    'tax' => $ivs->tax,
                    'amount' => $ivs->amount,
                    'performance' => $ivs->performance,
                    'description' => $ivs->description,
                    'type_id' => $ivs->type_id,
                ];
            });

            return response()->json($ivs, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao recuperar os dados do empréstimo'], 500);
        }
    }

    public function getUserInvestment()
    {
        $user = auth('api')->user();  // Ou auth()->user()


        // Recupera os investimentos do usuário com as relações necessárias
        $investments = UserInvestment::where('user_id', $user->id)
            ->with('investment') // Carrega os detalhes do investimento
            ->get();

        // Calcular o total investido
        $totalInvested = $investments->sum('amount');

        // Iterar sobre os investimentos para calcular o retorno diário
        $investmentsData = $investments->map(function ($investment) {
            // Cálculo do retorno diário para o investimento
            $retorno = calculateDailyReturn(
                $investment->amount,
                $investment->start_date,
                $investment->investment->performance,
                $investment->investment->tax
            );

            // Adicionar o valor calculado ao investimento
            return [
                'id' => $investment->id,
                'investment_id' => $investment->investment_id,
                'user_id' => $investment->user_id,
                'type_id' => $investment->type_id,
                'start_date' => $investment->start_date,
                'end_date' => $investment->end_date,
                'calculated_return' => $retorno, // Inclui o valor calculado aqui
                'amount' => $investment->amount,
                'investment' => $investment->investment, // Inclui os dados do investimento
            ];
        });

        // Retorna a resposta JSON com os dados
        return response()->json([
            'status' => 'success',
            'data' => [
                'investments' => $investmentsData,
                'totalInvested' => $totalInvested,
            ],
        ]);
    }


    public function userContractInvestment(Request $request, $investment_id)
    {
        $request->validate([
            'amount' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^\d{1,3}(?:\.\d{3})*(?:,\d{2})?$/', $value)) {
                    $fail('O campo ' . $attribute . ' tem um formato inválido.');
                }
            }],
            'investment_id' => 'required|exists:investments,id',
        ]);

        // Converter o valor para inteiro
        $amount = convertAmountToInt($request->amount);

        // Recuperar o usuário atual
        $user = auth('api')->user();  // Ou auth()->user()


        // Verificar saldo suficiente
        if ($amount > converterBalanceToInt($user->balance)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Saldo insuficiente para fazer investimento.',
            ], 400);
        }

        // Recuperar o investimento selecionado
        $selectedInvestment = Investment::findOrFail($request->investment_id);
        if ($selectedInvestment->amount > $amount) {
            return response()->json([
                'status' => 'error',
                'message' => 'O investimento mínimo é R$ ' . $selectedInvestment->amount,
            ], 400);
        }

        // Processar as datas
        $startDate = Carbon::now();
        $endDate = Carbon::now()->addMonths($selectedInvestment->term);

        DB::beginTransaction();

        try {
            // Criar o novo investimento
            $investment = new UserInvestment();
            $investment->user_id = $user->id;
            $investment->investment_id = $request->investment_id;
            $investment->type_id = Type::where('name', 'Investimentos')->first()->id;
            $investment->amount = $amount / 100;
            $investment->start_date = $startDate;
            $investment->end_date = $endDate;
            $investment->save();

            // Atualizar o saldo do usuário
            $user->balance -= $amount;
            $user->save();

            // Creditar o saldo ao destinatário
            $receiver = User::where('email', 'containvestimento@user.com')->first();
            $receiver->balance += $amount;
            $receiver->save();

            // Criar uma transação
            $transaction = new Transaction([
                'sender_id' => $user->id,
                'receiver_id' => $receiver->id,
                'type_id' => Type::where('name', 'Investimentos')->first()->id,
                'amount' => $amount / 100,
                'status' => Transaction::STATUS_SUCESSO,
                'id_transaction' => null,
                'type_transaction' => Transaction::TRANSFERENCIA,
                'operacao' => Transaction::SAIDA,
                'name' => 'Investimentos',
            ]);
            $transaction->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Investimento realizado com sucesso!',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Ocorreu um erro ao processar o investimento.',
            ], 500);
        }
    }
}

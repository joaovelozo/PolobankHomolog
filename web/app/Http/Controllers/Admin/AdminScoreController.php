<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Score;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Type;

class AdminScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
     {
        $this->transactionsProccess();

        $riskStatusToShow = $this->getOverallRiskStatus();

        // Buscar todos os scores com os detalhes do usuário e as transações associadas
        $scores = Score::with('transaction.user')->get();

        return view('admin.score.index', compact('scores', 'riskStatusToShow'));
    }

    private function transactionsProccess()
    {
        $excludedTypeIds = Type::whereIn('name', ['PIX', 'TED'])->pluck('id');

        $debitTransactions = Transaction::whereNotIn('type_id', $excludedTypeIds)->get();

        foreach ($debitTransactions as $transaction) {
            // Verificar se a transação foi bem-sucedida
            $success = $this->checkTransactionSuccess($transaction);

            // Verificar se o tipo da transação é 'Saldo Insuficiente'
            $isInsufficientBalance = $transaction->type === 'Saldo Insuficiente';

            // Determinar o status de risco com base no número de tentativas mal sucedidas
            $riskStatus = $this->determineRiskStatus($transaction, $isInsufficientBalance);

            // Salvar o resultado na tabela de Score
            $score = new Score();
            $score->transaction_id = $transaction->id;
            $score->status = $success ? 'Baixo Risco' : $riskStatus;
            $score->save();
        }
    }

    private function checkTransactionSuccess($transaction)
    {
        // Verificar se há um usuário associado à transação
        if ($transaction->user) {
            // Verificar se há saldo suficiente na conta do usuário
            $user = $transaction->user;

            // Verificar se o saldo é suficiente para a transação
            if ($user->balance >= $transaction->amount) {
                // Se houver saldo suficiente, marque a transação como bem-sucedida
                return true;
            } else {
                // Se não houver saldo suficiente, marque a transação como mal-sucedida
                return false;
            }
        } else {
            // Se não houver usuário associado, considere a transação como mal-sucedida
            return false;
        }
    }

    private function determineRiskStatus($transaction, $isInsufficientBalance)
    {
        $excludedTypeIds = Type::whereIn('name', ['PIX', 'TED'])->pluck('id');
        // Contar o número de transações mal sucedidas do tipo 'Saldo Insuficiente' para este usuário
         $failedAttempts = Transaction::join('types', 'transactions.type_id', '=', 'types.id')
                                      ->where('transactions.sender_id', $transaction->sender_id)
                                      ->whereNotIn('types.id', $excludedTypeIds)
                                      ->where('types.name', 'Saldo Insuficiente')
                                      ->count();


        // Definir o limite de tentativas mal sucedidas
        $maxFailedAttempts = 3;

        // Verificar se o número de tentativas mal sucedidas ultrapassou o limite
        if ($failedAttempts >= $maxFailedAttempts || $isInsufficientBalance) {
            // Se ultrapassar o limite, retornar 'Alto Risco'
            return 'Alto Risco';
        } else {
            // Caso contrário, retornar 'Baixo Risco'
            return 'Baixo Risco';
        }
    }

    private function getOverallRiskStatus()
    {
        // Contar o número total de transações bem-sucedidas
        $successfulTransactions = Score::where('status', 'Baixo Risco')->count();

        // Contar o número total de transações malsucedidas
        $failedTransactions = Score::where('status', 'Alto Risco')->count();

        // Definir o limite para considerar um status geral de risco
        $riskThreshold = 0.2; // Por exemplo, 20% das transações são consideradas de alto risco

        // Calcular a proporção de transações malsucedidas em relação ao total de transações
        $failureRatio = $failedTransactions / ($successfulTransactions + $failedTransactions);

        // Verificar se a proporção de transações malsucedidas ultrapassa o limite
        if ($failureRatio >= $riskThreshold) {
            // Se ultrapassar o limite, retornar 'Alto Risco'
            return 'Alto Risco';
        } else {
            // Caso contrário, retornar 'Baixo Risco'
            return 'Baixo Risco';
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
}

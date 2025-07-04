<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Type;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BalanceController extends Controller
{
    public function AddBalance()
    {
        $users = User::all();
        return view('admin.balance.add', compact('users'));
    }
    public function store(Request $request)
    {
        // Validação dos dados de entrada
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
        ]);

        // Encontre o usuário com base no ID fornecido
        $user = User::find($request->input('user_id'));

        if ($user) {
            // Atualize o saldo do usuário
            $newBalance = $user->balance + $request->input('amount');
            $user->update(['balance' => $newBalance]);

            $notification = [
                'message' => 'Saldo Adicionado Com Sucesso!',
                'alert-type' => 'success'
            ];

            return redirect()->back()->with($notification);
        } else {
            $notification = [
                'message' => 'Erro Ao Adicionar Saldo!',
                'alert-type' => 'danger'
            ];
            return redirect()->back()->with($notification);
        }
    }

    public function debit()
    {
        $users = User::all();
        $types = Type::all();
        return view('admin.debit.add',compact('users','types'));
    }
    //Debit Function
    public function DebitStore(Request $request)
    {
       //dd($request->input('type_id'));
        $adminId = Auth::user()->id;
        try {
            // Validação dos dados de entrada
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'amount' => 'required|numeric|min:0.01',
                'type_id' => 'required|exists:types,id', // Adicione validação para o type_id
            ]);
        
            // Encontre o usuário com base no ID fornecido
            $user = User::findOrFail($request->input('user_id'));
        
            // Verifique se o saldo do usuário é suficiente para debitar
            if ($user->balance >= $request->input('amount')) {
                // Atualize o saldo do usuário subtraindo o valor
                $newBalance = $user->balance - $request->input('amount');
                $user->balance = $newBalance;
        
                // Salvar o usuário
                $user->save();
        
                // Criar uma nova transação para registrar o débito
                Transaction::create([
                    'sender_id' => $user->id,
                    'receiver_id' => $adminId,
                    'amount' => $request->input('amount'),
                    'type_id' => $request->input('type_id'), // Inserir o type_id fornecido
                    // Adicione outros campos necessários
                ]);
        
                $notification = [
                    'message' => 'Saldo debitado com sucesso!',
                    'alert-type' => 'success'
                ];
        
                return redirect()->back()->with($notification);
            } else {
                // Criar uma nova transação para registrar o saldo insuficiente
                Transaction::create([
                    'sender_id' => $user->id,
                    'receiver_id' => $adminId,
                    'amount' => $request->input('amount'),
                    'type_id' => $request->input('type_id'), // Inserir o type_id fornecido
                    // Adicione outros campos necessários
                ]);
    
                $notification = [
                    'message' => 'Saldo insuficiente para o débito!',
                    'alert-type' => 'danger'
                ];
                return redirect()->back()->with($notification);
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
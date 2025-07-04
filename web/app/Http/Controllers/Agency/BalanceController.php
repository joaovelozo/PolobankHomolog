<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function AddBalance()
    {
        $users = User::all();
        return view('agency.balance.add', compact('users'));
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

            return redirect('/agency/dashboard')->with('success', 'Saldo adicionado com sucesso.');
        } else {
            return redirect('/agency/add-balance')->with('error', 'Usuário não encontrado.');
        }
    }
}



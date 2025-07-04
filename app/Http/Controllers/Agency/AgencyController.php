<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AgencyController extends Controller
{

    public function Dashboard()
    {
        $users = User::all();

        return view('agency.dashboard.index', compact('users',));
    }

    public function AgencyLogin()
    {
        return view('agency.auth.become');
    }


    public function Link()
    {
        $user = Auth::user();
        $agencyId = $user->agency_id;  // Pega o ID da agência a partir do usuário
        $planId = $user->plan_id;      // Pega o plan_id do usuário (ou agência)

        // Passa o agencyId e o planId como referência para a view
        return view('agency.link.index', ['agencyId' => $agencyId, 'planId' => $planId]);
    }

    public function UsersAgencyTransactions()
    {
        // Encontrar todos os usuários da agência pelo ID da agência
        $authUser = auth()->user();
        $users = User::where('agency_id', $authUser->agency_id)->get();

        $transactions = collect();

        // Iterar sobre cada usuário e agregar suas transações
        foreach ($users as $user) {
            $transactions = $transactions->merge($user->transactions);
        }

        return view('agency.transactions.index', compact('transactions'));
    }

    public function userAgencyTransaction($id)
    {
        // Encontrar o usuário pelo ID
        $user = User::findOrFail($id);

        // Verificar se o usuário pertence à agência do usuário autenticado
        $authUser = auth()->user();
        if ($user->agency_id !== $authUser->agency_id) {
            // Redirecionar ou retornar erro, pois o usuário não pertence à agência
        }

        $transactions = $user->transactions;

        return view('agency.transactions.show', compact('transactions'));
    }
}

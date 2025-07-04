<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Auth;

class AgencyManagerController extends Controller
{
    public function dashboard()
    {
        $transactions = Transaction::where('user_id', Auth::id())->orderBy('created_at', 'DESC')
            ->get();


        return view('agency.manager.dashboard', compact('transactions'));
    }

}

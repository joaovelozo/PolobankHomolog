<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subconta;
use App\Models\Transaction;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use PDF;

class StaController extends Controller
{
    public function staGenerate()
    {
        $transactions = Transaction::with(['sender', 'receiver'])->get();
        $users = User::all();

        $pdf = PDF::loadView('admin.sta.transactions', compact('transactions', 'users'));
        return $pdf->download('transactions.pdf');
    }
}

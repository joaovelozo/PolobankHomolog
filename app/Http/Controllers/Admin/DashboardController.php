<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Lending;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
   function index() : View {
    $users = User::count();
    $managerCount = User::where('role', 'manager')->count();

    $lendings = Lending::count();
    $transactions = Transaction::all();
    $agencies = Agency::count();
    $totalBalance = User::sum('balance');
    $totalTransactions = Transaction::sum('amount');


    return view('admin.dashboard.index', compact('users','transactions','agencies','lendings','managerCount','totalBalance','totalTransactions'));
   }




}

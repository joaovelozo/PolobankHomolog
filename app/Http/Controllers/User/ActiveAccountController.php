<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StarbankService;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\Pix;
use App\Models\Type;
use Auth;


class ActiveAccountController extends Controller
{

   public function __construct()
   {
      $this->middleware('auth');
   }
   public function checkout()
   {
      $user = Auth::user();

      // Verifica se o usuário já está ativo e redireciona para o dashboard
      if ($user->status == 'active') {
         return redirect()->route('dashboard');
      }
      // Inicia o serviço da Starkbank

      return view('auth.checkout');
   }
}

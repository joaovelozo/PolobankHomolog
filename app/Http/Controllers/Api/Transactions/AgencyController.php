<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Agency;

class AgencyController extends Controller
{
    public function getAgencyData()
    {
        {
            try{
                $user = Auth::user();
                if(!$user)
                {
                    return response()->json(['error' => 'Usuário Não Encontrado'],404);
                }
                $agency = $user->agency;
                return response()->json([
                    'agency'=>$agency,
                    
                ], 200);
                }catch(\Exception $e){
                    return response()->json(['error' => 'Erro ao recuperar dados do cartão'],500);
            }
        }
    }
}

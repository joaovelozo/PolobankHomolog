<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function getCardData()
    {
        {
            try{
                $user = auth('api')->user();  // Ou auth()->user()
                if(!$user)
                {
                    return response()->json(['error' => 'Usuário Não Encontrado'],404);
                }
                $card = $user->card;
                return response()->json([
                    'type'=> $card->type,
                    'validate' => $card->validate,
                    'number' =>$card->number,
                    'cvv' => $card->cvv,
                ], 200);
                }catch(\Exception $e){
                    return response()->json(['error' => 'Erro ao recuperar dados do cartão'],500);
            }
        }
    }
}

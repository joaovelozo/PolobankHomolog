<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Card;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // Obtém o ID da agência atualmente autenticada
    $agencyId = Auth::user()->agency_id;

    // Obtém todos os cartões pertencentes aos usuários dessa agência
    $card = Card::whereIn('user_id', function ($query) use ($agencyId) {
        $query->select('id')->from('users')->where('agency_id', $agencyId);
    })->get();

    // Retorna a visualização com os cartões específicos da agência
    return view('agency.card.index', compact('card'));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $agencyId = Auth::user()->agency_id;
        $users = User::where('agency_id', $agencyId)->get();

        return view('agency.card.create',compact('users'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Valida os dados do formulário
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id,agency_id,' . Auth::user()->agency_id,
            'type' => 'required',
            'validate' => 'required|date',
        ]);
    
        // Gera um número de CVV aleatório com 3 dígitos
        $cvv = mt_rand(100, 999);
    
        // Gera um número de cartão aleatório com 16 dígitos
        $cardNumber = '';
        for ($i = 0; $i < 16; $i++) {
            $cardNumber .= mt_rand(0, 9);
        }
    
        // Cria um novo cartão com os dados recebidos, o CVV e o número do cartão gerado
        $card = new Card();
        $card->user_id = $request->user_id;
        $card->type = $request->type;
        $card->validate = $request->validate;
        $card->cvv = $cvv;
        $card->number = $cardNumber;
        $card->save();
    
        // Redireciona de volta à página de cartões ou aonde for apropriado
        return redirect()->route('agencycard.index')->with('success', 'Cartão criado com sucesso!');
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
        $card = Card::findOrFail($id);
        $agencyId = Auth::user()->agency_id;
        $users = User::where('agency_id', $agencyId)->get();
        return view('agency.card.edit',compact('card','users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = $request->validate([
            'type' => 'required',
        ]);

        $card = Card::findOrFail($id);

        $card->type = $request ->type;
        $card->save();

        $notification = [
            'message' => 'Tipo Alterado com Sucesso!',
            'alert-type' => 'success'
        ];

        return back()->with( $notification);


        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\OpenContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpenContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Recupera o usuário logado
        $user = Auth::user();
    
        // Verifica se o usuário possui um contrato associado
        if ($user->openContract) {
            // Se o usuário tiver um contrato associado, recupere-o
            $ctr = $user->openContract;
            return view('users.opencontract.index', compact('ctr'));
        } else {
            // Se o usuário não tiver um contrato associado, retorne uma mensagem de erro ou redirecione-o para uma página adequada
            return back()->withErrors(['message' => 'Você não possui um contrato associado.']);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

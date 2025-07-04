<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Type;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class ScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obter o ID da agência do usuário autenticado
        $agencyId = Auth::user()->agency_id;
    
        // Buscar todos os clientes da agência
        $clients = User::where('agency_id', $agencyId)->get();
    
        // Obter os IDs dos clientes
        $clientsIds = $clients->pluck('id');
    
        // Obter os IDs dos tipos PIX e TED
        $excludedTypeIds = Type::whereIn('name', ['PIX', 'TED'])->pluck('id');
    
        // Buscar todas as transações para os clientes da agência, exceto PIX e TED
        $debitTransactions = Transaction::whereIn('sender_id', $clientsIds)
            ->whereNotIn('type_id', $excludedTypeIds)
            ->get();
    
        return view('agency.score.index', compact('clients', 'debitTransactions'));
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

<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Reply;

class AgencyReplyController extends Controller
{



    public function index()
    {
    // Obtém a agência do usuário autenticado
    $agency = auth()->user()->agency;

    // Obtém todos os tickets abertos associados aos usuários com a função 'user' e à agência
    $tks = Ticket::whereHas('user', function ($query) use ($agency) {
        $query->where('role', 'user')->where('agency_id', $agency->id);
    })->get();

    
        return view('agency.reply.index', compact('tks'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $id)
    {
     $ticket = Ticket::findOrFail($id);
     return view('agency.reply.create', compact('ticket'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $id)
    {
        $ticket = Ticket::findOrFail($id);

        $reply = new Reply();
        $reply->user_id = auth()->user()->id;
        $reply->ticket_id = $ticket->id;
        $reply->response = $request->input('response');
        $reply->save();

        if($request->has('status')) {
            $ticket->status = $request->input('status');
            $ticket->save();
        }

        $notification = [
            'message' => 'Chamado Respondido com Sucesso!',
            'alert-type' => 'success'
        ];

        return redirect()->route('agencyreply.index')->with($notification);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('agency.reply.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('agency.reply.edit',compact('ticket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ticket = Ticket::findOrFail($id);
    
        // Criar uma nova resposta
        $reply = new Reply();
        $reply->user_id = auth()->user()->id; // Assumindo que você tenha autenticação de usuário
        $reply->ticket_id = $ticket->id;
        $reply->response = $request->input('response');
        $reply->save();
    
        // Verificar e atualizar o estado do ticket, se necessário
        if ($request->has('status')) {
            $ticket->status = $request->input('status');
            $ticket->save();
        }
    
        // Redirecionar de volta à página de tickets ou fazer qualquer outra ação necessária
        return redirect()->route('agencyreply.index')->with('success', 'Resposta adicionada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

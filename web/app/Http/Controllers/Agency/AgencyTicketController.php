<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Ticket;
use App\Models\Reply;

class AgencyTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();

        // Recupera apenas os documentos associados ao usuário autenticado
        $tks = Ticket::where('user_id', $userId)->get();
        
         // Recupera as respostas associadas aos tickets do usuário logado
        $ticketIds = $tks->pluck('id'); // Obtém os IDs dos tickets
        $rps = Reply::whereIn('ticket_id', $ticketIds)->get();

        

        return view('agency.tickets.index',compact('tks','rps'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('agency.tickets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        
        $ticket = new Ticket();
        $ticket->user_id = auth()->user()->id;
        $ticket->title = $request->input('title');
        $ticket->description = $request->input('description');
        
        // Gera o número do protocolo
        $year = date('y'); // Obtém os dois últimos dígitos do ano atual
        $lastProtocol = Ticket::whereYear('created_at', '=', date('Y'))->max('protocol'); // Obtém o último protocolo do ano atual
        $nextProtocol = ($lastProtocol) ? $lastProtocol + 1 : 1; // Calcula o próximo número do protocolo
        $protocol = sprintf('%03d', $nextProtocol); // Formata o protocolo com três dígitos
        
        $ticket->protocol = $protocol . $year; // Combina o protocolo com os dois últimos dígitos do ano atual
    
        // Salva o ticket no banco de dados
        $ticket->save();
    
        $notification = [
            'message' => 'Em breve responderemos!',
            'alert-type' => 'success'
        ];
    
        // Redireciona para uma página de sucesso ou faz qualquer outra ação desejada
        return redirect()->route('agencyticket.index')->with($notification);
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

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Auth;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Verifica se o usuário está autenticado
        if (Auth::check()) {
            // Obtém o ID do usuário autenticado
            $userId = Auth::id();

            // Recupera apenas os documentos associados ao usuário autenticado
            $docs = Document::where('user_id', $userId)->get();

            return view('users.documents.index', compact('docs'));
        } else {
            // Caso não haja usuário autenticado, redireciona ou retorna uma mensagem de erro
            return redirect()->route('login')->with('error', 'Você precisa estar logado para acessar seus documentos.');
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
        $docs = Document::findOrFail($id);
        return view('users.documents.show',compact('docs'));
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

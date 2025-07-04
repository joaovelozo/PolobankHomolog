<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DocumentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $docs = Document::all();
        return view('admin.documents.index',compact('docs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('role', 'user')->get();
    
        return view('admin.documents.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'client_id' => 'required|exists:users,id',
        'file' => 'required|file|mimes:pdf,png,jpeg,jpg,gif|max:2048',
        'description' => 'nullable|string', // Novo campo de validação para a descrição
    ]);

    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/docs'), $fileName); // Move o arquivo para public/uploads/docs
    
        // Salvar o caminho do arquivo e relacionar com o usuário no banco de dados
        $document = new Document();
        $document->user_id = $request->client_id;
        $document->file = 'uploads/docs/' . $fileName; // Salva o caminho relativo no banco
    
        // Verifica se há descrição na requisição e, se houver, atribui ao documento
        if ($request->has('description')) {
            $document->description = $request->input('description');
        }
    
        $document->save();
    
        $notification = [
            'message' => 'Documento Criado com Sucesso!',
            'alert-type' => 'success'
        ];
    
        return redirect()->route('docs.index')->with($notification);
    }
    
    $notification = [
        'message' => 'Falha ao Carregar o Documento!',
        'alert-type' => 'error'
    ];
    
    return redirect()->back()->with($notification);
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
    $document = Document::findOrFail($id);

    // Remove o arquivo associado, se existir
    if ($document->file && Storage::exists($document->file)) {
        Storage::delete($document->file);
    }

    // Remove o documento do banco de dados
    $document->delete();

    $notification = [
        'message' => 'Documento removido com sucesso!',
        'alert-type' => 'success'
    ];

    return redirect()->route('docs.index')->with($notification);
}
}
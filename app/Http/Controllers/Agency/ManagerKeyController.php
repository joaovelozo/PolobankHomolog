<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\PixKey;
use App\Services\MyBank\PixService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerKeyController extends Controller
{
    protected $pixKeyService;

    public function __construct(PixService $pixKeyService)
    {
        $this->pixKeyService = $pixKeyService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        // Só pega as chaves do usuário logado
        $keys = PixKey::where('user_id', $user->id)->get();

        return view('agency.pixkey.index', compact('keys'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('agency.pixkey.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'type' => 'required|in:EVP,DOCUMENT,PHONE,EMAIL',
        'key' => 'nullable|string|max:255',
    ]);

    // Se for EVP, o Pix é gerado automaticamente pelo serviço
    $payload = ['type' => $request->input('type')];
    if ($payload['type'] !== 'EVP') {
        $payload['key'] = $request->input('key'); // o usuário precisa informar
    }

    // Chama o serviço para criar na API Pix
    $resposta = $this->pixKeyService->createKey($payload);

    // Salva no banco de dados a nova chave Pix junto com o usuário
    PixKey::create([
        'user_id' => $user->id,
        'type' => $resposta['tipo_chave'] ?? $payload['type'],
        'key' => $resposta['chave'] ?? $payload['key'],
    ]);

    return redirect()
        ->route('mkey.index')
        ->with('message', $resposta['message'] ?? 'Chave criada com sucesso');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $pixKey = PixKey::where('user_id', $user->id)->findOrFail($id);

        return view('agency.pixkey.show', compact('pixKey'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();
        $pixKey = PixKey::where('user_id', $user->id)->findOrFail($id);

        return view('agency.pixkey.edit', compact('pixKey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();

        $pixKey = PixKey::where('user_id', $user->id)->findOrFail($id);

        $request->validate([
            'key' => 'required|string|max:255|unique:pix_keys,key,' . $pixKey->id, // ignora o id atual
            'type' => 'required|string|max:50',
        ]);

        $pixKey->update([
            'key' => $request->key,
            'type' => $request->type,
        ]);

        return redirect()->route('mkey.index')->with('success', 'Chave PIX atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();

        $pixKey = PixKey::where('user_id', $user->id)->findOrFail($id);

        $pixKey->delete();

        return redirect()->route('mkey.index')->with('success', 'Chave PIX deletada com sucesso.');
    }
}

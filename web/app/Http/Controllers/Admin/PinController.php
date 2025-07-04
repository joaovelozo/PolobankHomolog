<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pins = Pin::where('user_id', auth()->id())->get();
        $pinadminId = auth()->id(); // Supondo que o ID do PIN esteja relacionado ao ID do usuário
        return view('admin.pin.index', compact('pins', 'pinadminId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Pin::where('user_id', auth()->id())->exists()) {
            return redirect()->route('pinadmin.index')->with('error', 'Você já cadastrou um PIN.');
        }

        $request->validate([
            'pin' => 'required|digits:4',
        ]);

        Pin::create([
            'user_id' => auth()->id(),
            'pin' => Hash::make($request->pin),
        ]);

        $notification = [
            'message' => 'Pin de transação Criado com Sucesso',
            'alert-type' => 'success'
        ];
        return redirect()->route('pinadmin.index')->with($notification);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pin = Pin::findOrFail($id);

        // Verifique se o PIN pertence ao usuário logado
        if ($pin->user_id !== auth()->id()) {
            return redirect()->route('pinadmin.index')->with('error', 'Acesso negado.');
        }

        return view('admin.pin.edit', compact('pin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'pin' => 'required|digits:4',
        ]);

        $pin = Pin::findOrFail($id);

        if ($pin->user_id !== auth()->id()) {
            return redirect()->route('pinadmin.index')->with('error', 'Acesso negado.');
        }

        $pin->update([
            'pin' => Hash::make($request->pin),
        ]);

        $notification = [
            'message' => 'Pin de transação Atualizado com Sucesso',
            'alert-type' => 'success'
        ];
        return redirect()->route('pinadmin.index')->with($notification);
    }
}

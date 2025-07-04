<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $msn = Message::all();
        return view('admin.message.index', compact('msn'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.message.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'url' => 'nullable|url',
         
        ]);

        $validatedData['url'] = $request->input('url');

        $msn = Message::create($validatedData);

        return redirect()->route('message.index')->with('success', 'Menssagem Criada com Sucesso');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $msn = Message::findOrFail($id);
        return view('admin.message.show', compact('msn'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $msn = Message::findOrFail($id);
        return view('admin.message.edit', compact('msn'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'url' => 'nullable|url',
   
        ]);

        $msn = Message::findOrFail($id);
        $msn->update($validatedData);

        return redirect()->route('message.index')->with('success', 'Menssagem atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $msn = Message::findOrFail($id);
        $msn->delete();

        return redirect()->route('message.index')->with('success', 'Menssagem Apagada com Sucesso!');
    }
}

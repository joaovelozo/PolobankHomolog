<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comunication;
use Illuminate\Http\Request;

class ComunicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $com = Comunication::all();
        return view('admin.comunication.index', compact('com'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.comunication.create');
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

        $com = Comunication::create($validatedData);

        return redirect()->route('agencycom.index')->with('success', 'Menssagem Criada com Sucesso');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $com = Comunication::findOrFail($id);
        return view('admin.comunication.show', compact('com'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $com = Comunication::findOrFail($id);
        return view('admin.comunication.edit', compact('com'));
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

        $com = Comunication::findOrFail($id);
        $com->update($validatedData);

        return redirect()->route('agencycom.index')->with('success', 'Menssagem atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $com = Comunication::findOrFail($id);
        $com->delete();

        return redirect()->route('agencycom.index')->with('success', 'Menssagem Apagada com Sucesso!');
    }
}

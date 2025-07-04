<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Inmate;
use Illuminate\Http\Request;

class InmateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('site.inmate.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('site.inmate.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required',
            'document' => 'required',
            'phone'=>'required',
            'process' => 'required',
            'attorney' => 'required',
            'number' => 'required',
            'contact' => 'required',


        ]);

        $inm = Inmate::create($validatedData);


        $notification = [
            'message' => 'Seus Dados Foram Enviandos!',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
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

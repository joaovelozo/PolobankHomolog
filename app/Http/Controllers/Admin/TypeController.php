<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Type;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $typs = Type::all();
        return view('admin.types.index', compact('typs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('admin.types.create'); 
    }

    /**
     * Store a newly created resource in storage.
     */
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255', 
        ]);


       Type::create($validatedData);

        $notification = [
            'message' => 'Tipo Criado Com Sucesso!',
            'alert-type' => 'success'
        ];

        return redirect()->route('types.index')->with( $notification);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $typs = Type::findOrFail($id);
        return view('admin.types.show', compact('typs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $typs = Type::findOrFail($id);
        return view('admin.types.edit', compact('typs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
           
   
        ]);

        $typs = Type::findOrFail($id);
        $typs->update($validatedData);

        $notification = [
            'message' => 'Tipo Editado Com Sucesso!',
            'alert-type' => 'success'
        ];


        

        return redirect()->route('types.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $typs = Type::findOrFail($id);
        $typs->delete();

        $notification = [
            'message' => 'Tipo Apagado Com Sucesso!',
            'alert-type' => 'success'
        ];

        return redirect()->route('types.index')->with( $notification);
    }
}

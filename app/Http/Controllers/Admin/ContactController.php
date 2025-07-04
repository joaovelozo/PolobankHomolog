<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cont = Contact::all();
        return view('admin.contact.index',compact('cont'));
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
        $cnt = Contact::findOrFail($id);
        return view('admin.contact.show', compact('cnt'));
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
        $cnt = Contact::findOrFail($id);
        $cnt->delete();

        $notification = array(
            'message' => 'Contato Apagado Com Sucesso!',
            'alert-type' => 'success'
        );

        return redirect()->route('contact.index')->with($notification);
    }
}

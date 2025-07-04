<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TelemedPlan;
use Illuminate\Http\Request;

class AdminTelemedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $tele = TelemedPlan::all();
       return view('admin.telemedplan.index',compact('tele'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('admin.telemedplan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'period' => 'required',
            'amount' => 'required|numeric',

        ]);

        $description = $request->input('description');
        $clean_description = strip_tags($description);
        $decoded_description = html_entity_decode($clean_description, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        $tele = new TelemedPlan([

            'title' => $request->get('title'),
            'period' => $request->get('period'),
            'description' => $decoded_description,
            'amount' => $request->get('amount'),
        ]);

        $tele->save();

        $notification = [
            'message' => 'Plano Criado Com Sucesso!',
            'alert-type' => 'success'
        ];

        return redirect()->route('ateledicine.index')->with($notification);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tele = TelemedPlan::findOrFail($id);

        return view('admin.telemedplan.show', compact('tele'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tele = TelemedPlan::findOrFail($id);

        return view('admin.telemedplan.edit', compact('tele'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([

            'title' => 'required',
            'period' => 'required',
            'description' => 'required',
            'amount' => 'required|numeric',
        ]);

        $tele = TelemedPlan::findOrFail($id);
        $tele->title = $request->get('title');
        $tele->advantages = $request->get('advantages');
        $tele->description = $request->get('description');
        $tele->period = $request->get('period');
        $tele->amount = $request->get('amount');
        $tele->update();


        $notification = [
            'message' => 'Plano Atualizado Com Sucesso!',
            'alert-type' => 'success'
        ];

        return redirect()->route('ateledicine.index')->with($notification);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tele = TelemedPlan::findOrFail($id);
        $tele->delete();



        $notification = [
            'message' => 'Plano Apagado Com Sucesso!',
            'alert-type' => 'success'
        ];

        return redirect()->route('ateledicine.index')->with($notification);
    }
}

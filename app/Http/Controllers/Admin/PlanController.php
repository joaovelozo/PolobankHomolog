<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pls = Plan::all();
        return view('admin.plan.index', compact('pls'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('admin.plan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'advantages'=>'required',
            'period' => 'required',
            'amount' => 'required|numeric',

        ]);

        $description = $request->input('description');
        $clean_description = strip_tags($description);
        $decoded_description = html_entity_decode($clean_description, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        $pls = new Plan([

            'title' => $request->get('title'),
            'advantages' => $request->get('advantages'),
            'period' => $request->get('period'),
            'description' => $decoded_description,
            'amount' => $request->get('amount'),
        ]);

        $pls->save();

        $notification = [
            'message' => 'Plano Criado Com Sucesso!',
            'alert-type' => 'success'
        ];

        return redirect()->route('plans.index')->with($notification);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pls = Plan::findOrFail($id);

        return view('admin.plan.show', compact('pls'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pls = Plan::findOrFail($id);

        return view('admin.plan.edit', compact('pls'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([

            'title' => 'required',
            'advantages' => 'required',
            'period' => 'required',
            'description' => 'required',
            'amount' => 'required|numeric',
        ]);

        $plan = Plan::findOrFail($id);
        $plan->title = $request->get('title');
        $plan->advantages = $request->get('advantages');
        $plan->description = $request->get('description');
        $plan->period = $request->get('period');
        $plan->amount = $request->get('amount');
        $plan->save();


        $notification = [
            'message' => 'Plano Atualizado Com Sucesso!',
            'alert-type' => 'success'
        ];

        return redirect()->route('plans.index')->with($notification);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pls = Plan::findOrFail($id);
        $pls->delete();



        $notification = [
            'message' => 'Plano Apagado Com Sucesso!',
            'alert-type' => 'success'
        ];

        return redirect()->route('plans.index')->with($notification);
    }
}

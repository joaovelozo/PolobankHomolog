<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Type;
use Illuminate\Http\Request;


class InvestmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ivs = Investment::all();
        return view('admin.investment.index', compact('ivs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $typs = Type::all();
        return view('admin.investment.create', compact('typs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'term' => 'required',
            'tax'=>'required',
            'performance' => 'required',
            'description' => 'required',
            'amount' => ['required', 'string', function ($attribute, $value, $fail) {
                // Verifica se o valor é um formato monetário válido
                if (!preg_match('/^\d{1,3}(?:\.\d{3})*(?:,\d{2})?$/', $value)) {
                    $fail('O campo ' . $attribute . ' tem um formato inválido.');
                }
            }],
            'type_id' => 'required|exists:types,id'
        ]);

        $description = $request->input('description');
        $clean_description = strip_tags($description);
        $decoded_description = html_entity_decode($clean_description, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $amount = convertAmountToInt($request->amount);
        $ivs = new Investment([
            'type_id' => $request->get('type_id'), // Obtenha o valor do type_id do formulário
            'title' => $request->get('title'),
            'term' => $request->get('term'),
            'tax' => $request->get('tax'),
            'performance' => $request->get('performance'),
            'description' => $decoded_description,
            'amount' =>    $amount/100,
        ]);

        $ivs->save();

        $notification = [
            'message' => 'Investimento Criado Com Sucesso!',
            'alert-type' => 'success'
        ];

        return redirect()->route('admininvestment.index')->with($notification);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ivs = Investment::findOrFail($id);
       
        return view('admin.investments.show', compact('ivs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ivs = Investment::findOrFail($id);
        $typs = Type::all();
        return view('admin.investment.edit', compact('ivs','typs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'type_id' => 'required|exists:types,id', // Se desejar permitir a edição do tipo também, adicione esta validação
            'title' => 'required',
            'term' => 'required',
            'tax' => 'required',
            'performance' => 'required',
            'description' => 'required',
            'amount' => ['required', 'string', function ($attribute, $value, $fail) {
                // Verifica se o valor é um formato monetário válido
                if (!preg_match('/^\d{1,3}(?:\.\d{3})*(?:,\d{2})?$/', $value)) {
                    $fail('O campo ' . $attribute . ' tem um formato inválido.');
                }
            }],
        ]);
        $amount = convertAmountToInt($request->amount);
    
        $investment = Investment::findOrFail($id);
        $investment->type_id = $request->get('type_id'); // Se o tipo não deve ser editável, remova esta linha
        $investment->title = $request->get('title');
        $investment->term = $request->get('term');
        $investment->tax = $request->get('tax');
        $investment->performance = $request->get('performance');
        $investment->description = $request->get('description');
        $investment->amount = $amount/100;
        $investment->save();

        
        $notification = [
            'message' => 'Investimento Atualizado Com Sucesso!',
            'alert-type' => 'success'
        ];
    
        return redirect()->route('admininvestment.index')->with($notification);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ivs = Investment::findOrFail($id);
        $ivs->delete();
        $notification = [
            'message' => 'Investimento Apagado Com Sucesso!',
            'alert-type' => 'success'
        ];

        return redirect()->route('admininvestment.index')->with($notification);
    }
}

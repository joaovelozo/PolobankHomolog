<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\User;

class ContractsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ctr = Contract::all();
        return view('admin.contracts.index', compact('ctr'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'user');
        })->get();
        return view('admin.contracts.create',compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'options' => 'required|in:yes,no',
        ]);
    
        $contract = new Contract();
        $contract->title = $validatedData['title'];
        $contract->options = $validatedData['options'];
    
        if ($validatedData['options'] === 'yes') {
            $users = User::where('role', 'user')->get();
    
            foreach ($users as $user) {
                // Gerar o conteúdo do contrato com base nos dados do usuário
                $contractContent = "Nome Completo: {$user->name}\n";
                $contractContent .= "CPF: {$user->cpfCnpj}\n";
                $contractContent .= "Data de Nascimento: {$user->birthdate}\n";
                $contractContent .= "Profissão: {$user->profession}\n";
                $contractContent .= "Sexo: {$user->gender}\n";
                $contractContent .= "Logradouro: {$user->address}\n";
                $contractContent .= "Número: {$user->number}\n";
                $contractContent .= "Complemento: {$user->complement}\n";
                $contractContent .= "Bairro: {$user->neighborhood}\n";
                $contractContent .= "Cidade: {$user->city}\n";
                $contractContent .= "CEP: {$user->zipcode}\n";
                $contractContent .= "Telefone: {$user->phone}\n";
                $contractContent .= "Email: {$user->email}\n";
                $contractContent .= "Renda Mensal: {$user->income}\n\n";
    
                // Associar o ID do usuário ao contrato
                $contract->users()->attach($user->id);
    
                // Salvar o contrato
                $contract->content = $contractContent;
                $contract->save();
            }
        }
    
        $notification = [
            'message' => 'Contratos Criados com Sucesso!',
            'alert-type' => 'success'
        ];
    
        return redirect()->route('contracts.index')->with($notification);
    }
    
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ctr = Contract::findOrFail($id);
        return view('admin.contracts.show', compact('ctr'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $contract = Contract::findOrFail($id);
        return view('admin.contracts.edit', compact('contract'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'option' => 'required|in:yes,no',
        ]);

        $contract = Contract::findOrFail($id);
        $contract->title = $validatedData['title'];
        $contract->content = $validatedData['content'];
        $contract->option = $validatedData['option'];
        $contract->save();

        $notification = [
            'message' => 'Contrato Atualizado Com Sucesso!',
            'alert-type' => 'success'
        ];

        return redirect()->route('contracts.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $contract = Contract::findOrFail($id);
        $contract->delete();

        $notification = [
            'message' => 'Contrato Apagado Com Sucesso!',
            'alert-type' => 'success'
        ];

        return redirect()->route('contracts.index')->with($notification);
    }
}

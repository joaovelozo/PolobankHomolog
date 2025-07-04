<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Split;
use App\Models\User;
use Illuminate\Http\Request;

class SplitController extends Controller
{


    public function search(Request $request)
    {
        $search = $request->input('q'); // O termo de busca

        // Busca usuários que contenham o termo de busca no nome
        $users = User::where('name', 'LIKE', "%{$search}%")->limit(20)->get();

        return response()->json($users);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pay = Split::all();
        return view('admin.payment.index', compact('pay'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.payment.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
           // 'description' => 'required',
            'amount' => ['required', 'string', function ($attribute, $value, $fail) {
                // Verifica se o valor é um formato monetário válido
                if (!preg_match('/^\d{1,3}(?:\.\d{3})*(?:,\d{2})?$/', $value)) {
                    $fail('O campo ' . $attribute . ' tem um formato inválido.');
                }
            }],
            'user_id' => 'required|exists:users,id'
        ]);
        $amount = convertAmountToInt($request->amount);
        $amount = $amount / 100;
        $pay = new Split();
        $pay->title = $validatedData['title'];
        $pay->description = ' ';
        $pay->amount = $amount;
        $pay->user_id = $validatedData['user_id'];

        $pay->save();

        $notification = [
            'message' => 'Pagamento Criado Com Sucesso!',
            'alert-type' => 'success'
        ];

        return redirect()->route('adpayment.index')->with($notification);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $id)
    {
        $pay = Split::find($id);
        $selectedUser = $pay->recebedor; // Pega o usuário associado ao pagamento
        return view('admin.payment.edit', compact('pay','selectedUser'));
    }

    public function show( $id)
    {
        $pay = Split::find($id);
        return view('admin.payment.show', compact('pay'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $validatedData = $request->validate([
            'title' => 'required',
           // 'description' => 'required',
            'amount' => ['required', 'string', function ($attribute, $value, $fail) {
                // Verifica se o valor é um formato monetário válido
                if (!preg_match('/^\d{1,3}(?:\.\d{3})*(?:,\d{2})?$/', $value)) {
                    $fail('O campo ' . $attribute . ' tem um formato inválido.');
                }
            }],
            'user_id' => 'required|exists:users,id'
        ]);

        $pay = Split::find($id);
        if (!$pay) {
            return redirect()->back()->with('error', 'Pagamento não encontrado.');
        }
        $amount = convertAmountToInt($request->amount);
        $amount = $amount / 100;
        $pay->title = $validatedData['title'];
        $pay->amount = $amount;
        $pay->description = ' ';
        $pay->user_id = $validatedData['user_id'];
        $pay->save();
        $notification = [
            'message' => 'Pagamento editado Com Sucesso!',
            'alert-type' => 'success'
        ];

        return redirect()->route('adpayment.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
       // Remove o investimento do usuário
       $pay = Split::findOrFail($id);
       $pay->delete();

       return redirect()->route('adpayment.index')
           ->with('success', 'Pagamento removido com sucesso!');
    }
}

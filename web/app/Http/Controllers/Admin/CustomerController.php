<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Agency;
use Spatie\Permission\Models\Role;
use Hash;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $client = User::findOrFail($id);
        $agencies = Agency::latest()->get();
     
        return view('admin.clients.edit', compact('client', 'agencies'));
    }
    
    public function update(Request $request, $id)
    {
        try {
            $client = User::findOrFail($id);

            $validatedData = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|unique:users,email,' . $client->id,
                'document' => 'sometimes|required|string|max:14|unique:users,document,' . $client->id,
                'birthdate' => 'sometimes|required|string|max:255',
                'gender' => 'sometimes|required|string|max:255',
                'profession' => 'sometimes|required|string|max:255',
                'income' => 'sometimes|required|string|max:255', // Corrigi o typo aqui (de 'icome' para 'income')
                'phone' => 'sometimes|required|string|max:20',
                'address' => 'sometimes|required|string|max:255',
                'number' => 'sometimes|required|string|max:50',
                'zipcode' => 'sometimes|required|string|max:255',
                'neighborhood' => 'sometimes|required|string|max:20',
                'city' => 'sometimes|required|string|max:20',
                'state' => 'sometimes|required|string|max:20',
                'complement' => 'sometimes|required|string|max:20',
                'password' => 'nullable|string|min:8|confirmed', // Alterado para 'nullable' para permitir que seja opcional
                'agency_id' => 'sometimes|exists:agencies,id',
            ]);

            $dataToUpdate = [
                'agency_id' => $validatedData['agency_id'],
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'document' => $validatedData['document'],
                'birthdate' => $validatedData['birthdate'],
                //'gender' => $validatedData['gender'],
                //'profession' => $validatedData['profession'],
                //'income' => $validatedData['income'],
                'phone' => $validatedData['phone'],
                'address' => $validatedData['address'],
                'number' => $validatedData['number'],
                'zipcode' => $validatedData['zipcode'],
                'neighborhood' => $validatedData['neighborhood'],
                'city' => $validatedData['city'],
                'state' => $validatedData['state'],
                //'complement' => $validatedData['complement'],
            ];

            // Verifica se uma nova senha foi fornecida
            if ($request->filled('password')) {
                $dataToUpdate['password'] = bcrypt($validatedData['password']);
            }

            $client->update($dataToUpdate);

            $notification = [
                'message' => 'Usuário atualizado com sucesso!',
                'alert-type' => 'success'
            ];
        } catch (\Exception $e) {
            // Tratamento de erro genérico
            $notification = [
                'message' => 'Erro ao atualizar o usuário: ' . $e->getMessage(),
                'alert-type' => 'error'
            ];
        }

        return redirect()->back()->with($notification);
    }


    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function block(string $id)
{
    try {
        $client = User::findOrFail($id);
        
        // Definir o status do usuário como 'inactive' para bloquear
        $client->status = 'inactive';
        $client->save();
        
        // Se necessário, adicione outras ações aqui, como revogar o acesso ou desassociar transações

          $notification = [
            'message' => 'Usuário Bloqueado Com Sucesso ',
            'alert-type' => 'error'
        ];
        return redirect()->back()->with($notification);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return redirect()->back()->with('error', 'Usuário não encontrado.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Ocorreu um erro ao bloquear o usuário.');
    }
}
public function unblock($id)
    {
        try {
            $client = User::findOrFail($id);
            $client->status = 'active'; // Altera o status para 'active' para desbloquear o cliente
            $client->save();

            $notification = [
                'message' => 'Usuário Desbloqueado Com Sucesso ',
                'alert-type' => 'error'
            ];

            return redirect()->back()->with($notification);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Cliente não encontrado.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocorreu um erro ao desbloquear o cliente.');
        }
    }

    public function UserAgencyTransaction()
    {
        // Encontrar todos os usuários da agência pelo ID da agência
        $authUser = auth()->user();
        $users = User::where('agency_id', $authUser->agency_id)->get();

        $transactions = collect();

        // Iterar sobre cada usuário e agregar suas transações
        foreach ($users as $user) {
            $transactions = $transactions->merge($user->transactions);
        }

        return view('admin.clients.index', compact('transactions'));
    }
}

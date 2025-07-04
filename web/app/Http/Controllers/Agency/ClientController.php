<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('agency.clients.index');
    }


    public function getClients(Request $request)
    {
        $agencyId = Auth::user()->agency_id;

        // Parâmetros de busca e paginação do DataTables
        $search = $request->input('search.value');  // Valor da pesquisa
        $length = $request->input('length');        // Número de registros por página
        $start = $request->input('start');          // Índice inicial para paginação

        // Query inicial para buscar clientes da agência
        $query = User::where('agency_id', $agencyId)
                ->with(['transactions' => function ($query) {
                    // Carregar apenas transações com o nome 'Ativação conta'
                    $query->where('name', 'Ativação conta');
                }])
            ->when($search, function ($query) use ($search) {
                // Condição de busca, aplicando pesquisa por nome, email ou cpfCnpj
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('document', 'like', "%{$search}%");
            });

        // Contagem total de registros (sem filtros)
        $totalData = User::where('agency_id', $agencyId)->count();

        // Contagem de registros filtrados
        $totalFiltered = $query->count();

        // Paginação (limite e deslocamento)
        $clients = $query->offset($start)
            ->limit($length)
            ->get();

        // Preparar dados para retorno ao DataTables
        $data = [];
        foreach ($clients as $client) {
            $nestedData['name'] = $client->name;
            $nestedData['status'] = $client->status;
            $nestedData['cpfCnpj'] = $client->document;
            $nestedData['email'] = $client->email;
            $nestedData['account'] = $client->account;
            $nestedData['balance'] = 'R$' . number_format($client->balance(), 2, ',', '.');
            $nestedData['created_at'] = $client->created_at ? $client->created_at->format('d/m/Y H:i:s') : '';
            $nestedData['last_login'] = $client->last_login ? $client->last_login->format('d/m/Y H:i:s') : 'Nenhum Acesso Registrado';
            $nestedData['status_ativacao'] = isset($client->transactions[0]) ? $client->transactions[0]->getStatusDescription() : '';
            $nestedData['data_ativacao'] = isset($client->transactions[0]) ? $client->transactions[0]->created_at->format('d/m/Y H:i:s') : 'Sem transação de ativação';


            // Ações (botões)
            $nestedData['actions'] = '
            <div style="display: flex; align-items: center;">';

            if ($client->status === 'active') {
                $nestedData['actions'] .= '
                <form action="' . route('clients.block', $client->id) . '" method="POST" style="display: inline-block;">
                    ' . csrf_field() . method_field('PUT') . '
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="uil-eye-slash"></i>
                    </button>
                </form>';
            } else {
                $nestedData['actions'] .= '
                <form action="' . route('clients.unblock', $client->id) . '" method="POST" style="display: inline-block;">
                    ' . csrf_field() . method_field('PUT') . '
                    <button type="submit" class="btn btn-success btn-sm m-1">
                        <i  class="uil-check-circle"></i>
                    </button>
                </form>';
            }

            $nestedData['actions'] .= '
                <a class="btn btn-primary btn-sm m-1" href="' . route('clients.edit', $client->id) . '">
                    <i class="uil-edit"></i>
                </a>
                <a class="btn btn-success btn-sm m-1" href="' . route('agency.user.transaction', $client->id) . '">
                    <i class="uil-dollar-sign"></i>
                </a>
            </div>';


            $data[] = $nestedData;
        }

        // Retornar dados em formato JSON para o DataTables
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $client = User::findOrFail($id);
        return view('agency.clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
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

            ]);

            $dataToUpdate = [

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


        return redirect()->route('clients.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $client = User::findOrFail($id);

            // Antes de excluir o usuário, podemos verificar se existem transações associadas
            if ($client->transactions()->exists()) {
                // Se houver transações associadas, você pode optar por excluí-las ou tomar outra ação
                // Aqui, vou apenas desassociá-las do usuário
                $client->transactions()->update(['receiver_id' => null]);
            }

            // Agora podemos excluir o usuário
            $client->delete();

            return redirect()->back();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Tratar o caso em que o usuário não é encontrado
            return redirect()->back()->with('error', 'Usuário não encontrado.');
        } catch (\Exception $e) {
            // Tratar qualquer outra exceção que possa ocorrer
            return redirect()->back()->with('error', 'Ocorreu um erro ao excluir o usuário.');
        }
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
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AgencyController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $agencies = Agency::with('user')
            ->where('name', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->paginate(10);

        return view('admin.agency.index', compact('agencies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $managers = User::where('role', 'manager')->get();
        return view('admin.agency.create', compact('managers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required',
            'email' => 'required|email|unique:agencies',
            'address' => 'required',
            'user_id' => 'required|exists:users,id',
        ]);

        $number = '';

        // Geração do número da agência
        $number = '';
        for ($i = 0; $i < 5; $i++) {
            $number .= mt_rand(0, 9);
        }

        $agency = new Agency;
        $agency->name = $validatedData['name'];
        $agency->phone = $validatedData['phone'];
        $agency->email = $validatedData['email'];
        $agency->address = $validatedData['address'];
        $agency->number = $number;
        $agency->user_id = $validatedData['user_id'];
        $agency->save();


        $manager = User::findOrFail($validatedData['user_id']);
        $manager->agency_id = $agency->id;
        $manager->agency_number = $number;
        $manager->save();

        $notification = [
            'message' => 'Agência Criada Com Sucesso!',
            'alert-type' => 'success'
        ];

        return redirect()->route('agency.index')->with($notification);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $agency = Agency::findOrFail($id);
        return view('admin.agency.show', compact('agency'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $agency = Agency::findOrFail($id);
        $managers = User::where('role', 'manager')->get();
        return view('admin.agency.edit', compact('agency', 'managers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required',
            'email' => 'required|email|unique:agencies,email,' . $id,
            'address' => 'required',
            'user_id' => 'required|exists:users,id',
        ]);

        $agency = Agency::findOrFail($id);
        $agency->update($validatedData);

        $notification = [
            'message' => 'Agência Editada Com Sucesso!',
            'alert-type' => 'success'
        ];

        return redirect()->route('agency.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $agency = Agency::findOrFail($id);


        $users = User::where('agency_id', $id)->get();


        foreach ($users as $user) {
            $user->agency_id = null;
            $user->save();
        }


        $agency->delete();

        $notification = [
            'message' => 'Agência Apagada Com Sucesso!',
            'alert-type' => 'success'
        ];

        return redirect()->route('agency.index')->with($notification);
    }
    //Get Clients With Agency
    public function ClientAgency($id)
    {
        $agency = Agency::findOrFail($id);
        $clientsCount = User::where('agency_id', $agency->id)->count();
        $clients = User::where('agency_id', $agency->id)->paginate(100);
        return view('admin.agency.clients', compact('agency', 'clientsCount'));
    }



    public function getClients(Request $request, $id)
    {
        $agency = Agency::findOrFail($id);
        // Parâmetros de busca e paginação do DataTables
        $search = $request->input('search.value');  // Valor da pesquisa
        $length = $request->input('length');        // Número de registros por página
        $start = $request->input('start');          // Índice inicial para paginação
        // Query inicial para buscar clientes da agência
        $query = User::where('agency_id', $agency->id)
            ->with(['transactions' => function ($query) {
                // Carregar apenas transações com o nome 'Ativação conta'
                $query->where('name', 'Ativação conta');
            }])
            ->when($search, function ($query) use ($search) {
                // Condição de busca, aplicando pesquisa por nome, email ou cpfCnpj
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('documentNumber', 'like', "%{$search}%");
            });

        // Contagem total de registros (sem filtros)
        $totalData = User::where('agency_id', $agency->id)->count();

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
            $nestedData['account_id'] = $client->account_id;
            $nestedData['documentNumber'] = $client->documentNumber;
            $nestedData['email'] = $client->email;
            $nestedData['plano'] = $client->plan ? $client->plan->title : 'Sem plano';
            $nestedData['phoneNumber'] = $client->phoneNumber;
            $nestedData['balance'] = 'R$' . number_format($client->balance(), 2, ',', '.');
            $nestedData['perfil'] = $client->role === 'manager' ? 'Gerente' : ($client->role === 'user' ? 'Cliente' : 'Outro Papel');
            $nestedData['created_at'] = $client->created_at ? $client->created_at->format('d/m/Y H:i:s') : '';
            $nestedData['last_login'] = $client->last_login ? $client->last_login->format('d/m/Y H:i:s') : 'Nenhum Acesso Registrado';
            $nestedData['status_ativacao'] = isset($client->transactions[0]) ? $client->transactions[0]->getStatusDescription() : '';
            $nestedData['data_ativacao'] = isset($client->transactions[0]) ? $client->transactions[0]->created_at->format('d/m/Y H:i:s') : 'Sem transação de ativação';


            // Ações (botões)
            $nestedData['actions'] = '
            <div style="display: flex; align-items: center;">';

            if ($client->status === 'active') {
                $nestedData['actions'] .= '
                <form action="' . route('customer.block', $client->id) . '" method="POST" style="display: inline-block;">
                    ' . csrf_field() . method_field('PUT') . '
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="uil-eye-slash"></i>
                    </button>
                </form>';
            } else {
                $nestedData['actions'] .= '
                <form action="' . route('customer.unblock', $client->id) . '" method="POST" style="display: inline-block;">
                    ' . csrf_field() . method_field('PUT') . '
                    <button type="submit" class="btn btn-success btn-sm m-1">
                        <i  class="uil-check-circle"></i>
                    </button>
                </form>';
            }

            $nestedData['actions'] .= '
                <a class="btn btn-primary btn-sm m-1" href="' . route('customer.edit', $client->id) . '">
                    <i class="uil-edit"></i>
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


    //Confirm User Password

}

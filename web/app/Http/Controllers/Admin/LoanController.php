<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lending;
use App\Models\Loan;
use App\Models\Response;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loans = Loan::all();
        return view('admin.loan.index', compact('loans'));
    }

    public function lending()
    {
        $lds = Lending::with('loan')->get();
        return view('admin.lending.index',compact('lds'));
    }

    //Method Delete
    public function deleteLending($id)
    {
        $lending = Lending::find($id);

        if(!$lending) {
            return redirect()->back()->with('error', 'Empréstimo não encontrado');
        }

        $lending->delete();

        return redirect()->back()->with('success', 'Empréstimo excluído com sucesso');
    }

    public function showLending($id)
    {
        $lending = Lending::findOrFail($id);
        return view('admin.lending.show', compact('lending'));
    }


    public function respondToLending(Request $request, $id)
    {
        $lending = Lending::findOrFail($id);
    
        // Atualize apenas o status e a mensagem
        $lending->update([
            'status' => $request->status,
            'message' => $request->message,
            'url' => $request->url,
        ]);
    
        return redirect()->back()->with('message', 'Resposta enviada com sucesso!');
    }

    //Response  User
        public function ResponseUser($id)
        {
            $lds = Lending::findOrFail($id);
            $responses = $lds->responses()->with('user')->get();
        
            return view('admin.lending.response', compact('lds', 'responses'));
        }


        //store User Response
        public function StoreUserResponse(Request $request)
        {
            $lendingId = $request->input('lending_id');
            $responseText = $request->input('response');
            
            $response = new Response();
            $response->lending_id = $lendingId;
            $response->response = $responseText;
            $response->user_id = auth()->user()->id; // Substitua pelo ID do usuário atual
            $response->save();
        
            $notification = [
                'message' => 'Resposta Enviada com Sucesso!',
                'alert-type' => 'success'
            ];
        
            // Redirecionar de volta para a página de detalhes do empréstimo ou para onde desejar
            return redirect()->back()->with($notification);
        }
        

    //Show Documents
    public function ShowDocuments($id)
    {
        $lending = Lending::findOrFail($id);
        return view('admin.lending.documents',compact('lending'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.loan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       // Validando a entrada
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
    ]);

    // Limpando a descrição
    $description = $request->input('description');
    $clean_description = strip_tags($description);
    $decoded_description = html_entity_decode($clean_description, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

    // Criando o novo empréstimo
    $loan = new Loan();
    $loan->title = $request->input('title');
    $loan->description = $decoded_description;
    $loan->save();
        $notification = [
            'message' => 'Conteúdo do Empréstimo Criado Com Sucesso!',
            'alert-type' => 'success'
        ];

        return redirect()->route('loan.index')->with( $notification);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $loan = Loan::findOrFail($id);
        return view('admin.loan.show', compact('loan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $loan = Loan::findOrFail($id);
        return view('admin.loan.edit', compact('loan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        $loan = Loan::findOrFail($id);
        $loan->update($validatedData);


        
        $notification = [
            'message' => 'Conteúdo do Empréstimo Editado Com Sucesso!',
            'alert-type' => 'success'
        ];

        return redirect()->route('loan.index')->with( $notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    $loan = Loan::findOrFail($id);

    // Obtenha todos os lendings associados a este empréstimo
    $lendings = $loan->lendings;

    // Desvincule os lendings do empréstimo
    foreach ($lendings as $lending) {
        $lending->loan_id = null;
        $lending->save();
    }

    // Agora você pode excluir o empréstimo
    $loan->delete();

    $notification = [
        'message' => 'Conteúdo do Empréstimo Apagado Com Sucesso!',
        'alert-type' => 'success'
    ];

    return redirect()->route('loan.index')->with($notification);
}
}   

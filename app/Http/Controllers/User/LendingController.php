<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Lending;
use App\Models\Loan;
use App\Models\Response;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Storage;

class LendingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
     
        $userId = Auth::id();
        $leadings = Lending::with('loan')->where('user_id', $userId)->get();

        return view('users.lending.index', compact('leadings'));
    }

    public function Promoter()
    {
        $loans = Loan::all();
        return  view('users.lending.promoter', compact('loans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $loan = Loan::all();
        return view('users.lending.create',compact('loan'));
    }

    public function custom($loan_id)
    {
        $loan = Loan::find($loan_id);
        return view('users.lending.create',compact('loan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'cpf' => 'required|string',
            'email' => 'required|email',
            'amount' => 'required|numeric',
            'installments' => 'required|integer',
            'document' => 'nullable|image|max:2048',
            'proof' => 'nullable|image|max:2048',
            'invoice' => 'nullable|image|max:2048',
        ]);

        $loanId = $request->input('loan_id');
    
        $userId = auth()->id();
    
        $loanRequest = new Lending();
        $loanRequest->user_id = $userId;
        $loanRequest->loan_id = $loanId;
        $loanRequest->name = $request->name;
        $loanRequest->phone = $request->phone;
        $loanRequest->cpf = $validatedData['cpf'];
        $loanRequest->email = $validatedData['email'];
        $loanRequest->amount = $validatedData['amount'];
        $loanRequest->installments = $validatedData['installments'];
    
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('uploads');
            $loanRequest->document = $documentPath;
        }
    
        if ($request->hasFile('proof')) {
            $proofPath = $request->file('proof')->store('uploads');
            $loanRequest->proof = $proofPath;
        }
    
        if ($request->hasFile('invoice')) {
            $invoicePath = $request->file('invoice')->store('uploads');
            $loanRequest->invoice = $invoicePath;
        }
    
        $loanRequest->save();
    
        $notification = [
            'message' => 'Acompanhe o Status de Sua Solicitação',
            'alert-type' => 'success'
        ];
    
        return redirect()->route('lending.index')->with($notification);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Filtrando o empréstimo pelo ID do usuário autenticado
        $userId = Auth::id();
        $response = Response::where('lending_id', $id)->whereHas('lending', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->first();

        $lending = Lending::where('id', $id)->where('user_id', $userId)->firstOrFail();
        $status = $lending->status;
        $lendingId = $id;
        $lendings = Lending::where('user_id', $userId)->get();
        $responsesCount = $lending->responses->count();

        $lds = Lending::findOrFail($id);
        $responses = $lds->responses()->with('user')->get();

        return view('users.lending.show', compact('responses','responsesCount', 'lending', 'status', 'lendingId', 'response', 'lendings','lds'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lending = Lending::findOrFail($id);
   
        return view('users.lending.edit', compact('lending'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $validatedData = $request->validate([
        'document' => 'file|max:2048',
        'proof' => 'file|max:2048',
        'invoice' => 'file|max:2048',
    ]);

    $lending = Lending::findOrFail($id);

    // Processa e salva o documento
    if ($request->hasFile('document')) {
        $documentFile = $request->file('document');
        $documentFileName = time() . '_document.' . $documentFile->getClientOriginalExtension();
        $documentFile->move(public_path('documents'), $documentFileName);
        $lending->document = $documentFileName;
    }

    // Processa e salva o comprovante
    if ($request->hasFile('proof')) {
        $proofFile = $request->file('proof');
        $proofFileName = time() . '_proof.' . $proofFile->getClientOriginalExtension();
        $proofFile->move(public_path('proofs'), $proofFileName);
        $lending->proof = $proofFileName;
    }

    // Processa e salva a fatura
    if ($request->hasFile('invoice')) {
        $invoiceFile = $request->file('invoice');
        $invoiceFileName = time() . '_invoice.' . $invoiceFile->getClientOriginalExtension();
        $invoiceFile->move(public_path('invoices'), $invoiceFileName);
        $lending->invoice = $invoiceFileName;
    }

    $lending->save();

    // Redireciona com mensagem de sucesso
    $notification = [
        'message' => 'Documentos enviados com sucesso',
        'alert-type' => 'success'
    ];

    return redirect()->route('lending.index')->with($notification);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function SubmitQuestion(Request $request)
{

    Auth::user();
    $request->validate([
        'response' => 'required',
        'lending_id' => 'required|exists:lendings,id'
    ]);

    try {
        // Criar uma nova instância de LendingResponse para a pergunta do usuário
        $response = new Response();
        $response->lending_id = $request->lending_id;
        $response->response = $request->response;
        $response->user_id = auth()->user()->id; // Substitua pelo ID do usuário atual
        $response->save();

        $notification = array(
            'message' => 'Mensagem Enviada Com Sucesso!',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Erro ao enviar a pergunta. Por favor, tente novamente.');
    }
}
}
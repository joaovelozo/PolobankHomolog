<?php

namespace App\Http\Controllers\Api\Loan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Lending;
use Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;
class LoanController extends Controller
{
    public function getLoan()
    {
        try {
            $loans = Loan::all()->map(function ($loan) {
                return [
                    'id' => $loan->id,
                    'title' => $loan->title,
                    'description' =>$loan->description, // Decodifica as entidades HTML
                ];
            });

            return response()->json($loans, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao recuperar os dados do empréstimo'], 500);
        }
    }

    public function lendingRequest(Request $request, $loan_id)
    {
       $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'cpf' => 'required',
            'email' => 'required',
            'amount' => 'required',
            'installments' => 'required',

       ]);
       $lending = Lending::create([
        'user_id' => auth()->id(),
        'loan_id'=> $loan_id,
        'name' => $request->name,
        'cpf' => $request->cpf,
        'phone' => $request->phone,
        'email'=> $request->email,
        'amount' => $request->amount,
        'installments' => $request->installments,
        'status' => 'em analise',
        
       ]);
       return response()->json([
        'message' => 'Acompanhe o Status de Sua Solicitação',
        'date' => [
            'user_id' => $lending->user_id,
            'loan_id' => $loan_id,
            'name' => $lending->name,
            'cpf' => $lending->cpf,
            'phone' => $lending->phone,
            'email' => $lending->email,
            'amount' => $lending->amount,
            'installments' => $lending->installments,
            'status' => $lending->status,  // Incluindo o campo status na resposta
            'updated_at' => $lending->updated_at,
            'created_at' => $lending->created_at,
        ]
    ], 201);
    }
    public function lendingStatus()
    {
        try {
            $user = auth('api')->user();  // Ou auth()->user()

            if (!$user) {
                return response()->json(['message' => 'Usuário Não Encontrado'], 404);
            }
    
            $lendings = $user->lendings; // Note o plural aqui
            if ($lendings->isEmpty()) {
                return response()->json(['message' => 'Empréstimo Não Encontrado'], 404);
            }
    
            $response = $lendings->map(function($lending) use ($user) {
                return [ 
                    "id"=>$lending->id,
                    "amount" => $lending->amount,
                    'status' => $lending->status,
                    "created_at"=>$lending->created_at,
                 
                ];
            });
    
            return response()->json([
                'message' => 'Acompanhe o Status de Sua Solicitação',
                'date' => $response
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Erro ao recuperar os dados do empréstimo: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao recuperar os dados do empréstimo'], 500);
        }
    }
        

    public function lendingRequestDocuments(Request $request, $id)
    {
        try {
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
    
            // Retorna uma resposta de sucesso
            $notification = [
                'message' => 'Documentos enviados com sucesso, aguarde finalização da analise',
                'alert-type' => 'success'
            ];
    
            return response()->json($notification, Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            // Se houver uma exceção de validação, retornamos uma resposta com erros de validação
            return response()->json(['errors' => $e->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            // Se houver qualquer outra exceção, retornamos uma resposta de erro interno do servidor
            return response()->json(['message' => 'Erro interno do servidor'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
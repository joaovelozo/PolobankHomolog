<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\DatavalidService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FacialValidationController extends Controller
{
    protected $datavalidService;

    public function __construct(DatavalidService $datavalidService)
    {
        $this->datavalidService = $datavalidService;
    }

    public function validarFacial(Request $request)
    {
        // Validação do request
        $request->validate([
            'document' => 'required|string|min:14|max:14',
            'name' => 'required|string',
            'birthdate' => 'required|date_format:Y-m-d',
            'gender' => 'required|string|in:male,female,other',
            'address' => 'required|string',
            'number' => 'required|string',
            'complement' => 'nullable|string',
            'neighborhood' => 'required|string',
            'zipcode' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'document_front' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Aceita apenas imagens JPG/PNG com limite de 2MB
            'document_back' => 'required|image|mimes:jpg,jpeg,png|max:2048',  // Aceita apenas imagens JPG/PNG com limite de 2MB
            'selfie' => 'required|image|mimes:jpg,jpeg,png|max:2048',  // Aceita apenas imagens JPG/PNG com limite de 2MB
        ]);

        $dados = $request->all();
        
        if ($request->hasFile('selfie')) {
            $selfieImage = $request->file('selfie');
            $selfieImageName = time() . '-' . $selfieImage->getClientOriginalName();
            $storagePath = 'uploads/userdocs/documents/';
            $filePath = $selfieImage->storeAs($storagePath, $selfieImageName, 'public');
            $publicPath = storage_path('app/public/' . $filePath);
            $imageBase64 = base64_encode(file_get_contents($publicPath));
            $base64WithMime = $imageBase64;
        }

        // Dados da requisição
        $data = [
            'cpf' => str_replace(['.', '-'], '', $dados['document']),
            'validacao' => [
                'nome' =>  $dados['name'],
                'data_nascimento' => $dados['birthdate'],
                'sexo' => strtoupper(substr($dados['gender'], 0, 1)) ,
                'nacionalidade' => 1,
                'endereco' => [
                    'logradouro' => $dados['address'],
                    'numero' => $dados['number'],
                    'complemento' => $dados['complement'],
                    'bairro' =>  $dados['neighborhood'],
                    'cep' => $dados['zipcode'],
                    'municipio' => $dados['city'],
                    'uf' => $dados['state'],
                ],
                'biometria_facial' => [
                    'vivacidade' => true,
                    'formato' => 'PNG',
                    'base64' => $base64WithMime
                ]
            ]
        ];

        // Dados para teste
        $postData = [
            "cpf" => "25774435016",
            "validacao" => [
                "nome" => "Manuela Elisa da Mota",
                "data_nascimento" => "1975-06-04",
                "sexo" => "F",
                "nacionalidade" => 1,
                "endereco" => [
                    "logradouro" => "Rua Olívia Guedes Penteado",
                    "numero" => "941",
                    "bairro" => "Capela do Socorro",
                    "cep" => "04766900",
                    "municipio" => "São Paulo",
                    "uf" => "SP"
                ],
                'biometria_facial' => [
                    'vivacidade' => true,
                    'formato' => 'PNG',
                    'base64' => $base64WithMime
                ]
            ]
        ];
       // $postData = json_encode($postData);
       // dd($data);

        // Chamada ao serviço para validação facial
        $response = $this->datavalidService->enviarPFFacialV4Input($data);
        dd($response);
        exit();
        // Verificando a resposta da API
        if ($response && isset($response['resultado'])) {
            return response()->json([
                'message' => 'Validação facial realizada com sucesso',
                'data' => $response['resultado']
            ], 200);
        }

        return response()->json([
            'message' => 'Erro na validação facial',
            'error' => $response
        ], 400);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\MyBank\Auth\PersonalAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ManagerController extends Controller
{
    protected $personalAccountService;

    public function __construct(PersonalAccountService $personalAccountService)
    {
        $this->personalAccountService = $personalAccountService;

    }
    public function index()
    {
        $managers = User::where('role', 'manager')->get();
        return view('admin.manager.index', compact('managers'));
    }
    public function create()
    {
        return view('admin.manager.create');
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'username' => 'required|max:255',
            'nameMother' => 'required|max:255',
            'gender' => 'required',
            'idMaritalStatus' => 'required',
            'political' => 'required',
            'documentNumber' => [
                'string',
                'unique:users,documentNumber',
                function ($attribute, $value, $fail) {
                    $exists = User::where('documentNumber', $value)->exists();
                    if ($exists) {
                        $fail('CPF ou CNPJ já existe em nossa base de dados.');
                    }
                },
            ],
           'identityDocument' => 'required|unique:users',
            'issueDate' => 'required',
            'issuingAgency' => 'required',
            'issuingState' => 'required',
            'email' => 'required|email|unique:users',
            'birthdate' => 'required',
            'rent' => 'required',
            'phoneNumber' => 'required',
            'cellPhone' => 'required',
            'address' => 'required',
            'addressNumber' => 'required',
            'zipCode' => 'required',
            'neighborhood' => 'required',
            'city' => 'required',
            'state' => 'required',
            'password' => 'nullable|min:6|confirmed',
            'imageself' => ['required', 'image', 'mimes:jpg,jpeg,png'],
            'imagedoc' => ['required', 'image', 'mimes:,jpg,jpeg,png'],
            'imagedoc_verso' => ['required', 'image', 'mimes:jpg,jpeg,png'],
              'imagecomprovante' => ['required', 'image', 'mimes:jpg,jpeg,png'],
        ]);
        try {
        // Salva as imagens enviadas pelo usuário em storage
        $documentFrontBase64 = base64_encode(file_get_contents($request->file('imagedoc')->getRealPath()));
        $documentBackBase64 = base64_encode(file_get_contents($request->file('imagedoc_verso')->getRealPath()));
        $selfieBase64 = base64_encode(file_get_contents($request->file('imageself')->getRealPath()));
        $proofBase64 = base64_encode(file_get_contents($request->file('imagecomprovante')->getRealPath()));

        $cleanPhone = preg_replace('/[^0-9]/', '', $validatedData['phoneNumber']);
        $cleanDocument = preg_replace('/[^0-9]/', '', $validatedData['documentNumber']);

        $user = new User();

        $user->name = $validatedData['name'];
        $user->gender = $validatedData['gender'];
        $user->idMaritalStatus = $validatedData['idMaritalStatus'];
        $user->username = $validatedData['username'];
        $user->nameMother = $validatedData['nameMother'];
        $user->rent = $validatedData['rent'];
        $user->political = $validatedData['political'];
        $user->documentNumber =  $cleanDocument;
        $user->identityDocument = $validatedData['identityDocument'];
        $user->issueDate = $validatedData['issueDate'];
        $user->issuingAgency = $validatedData['issuingAgency'];
        $user->issuingState = $validatedData['issuingState'];
        $user->birthdate = $validatedData['birthdate'];
        $user->phoneNumber = $cleanPhone;
        $user->address = $validatedData['address'];
        $user->addressNumber = $validatedData['addressNumber'];
        $user->zipCode = $validatedData['zipCode'];
        $user->neighborhood = $validatedData['neighborhood'];
        $user->city = $validatedData['city'];
        $user->state = $validatedData['state'];
        $user->email = $validatedData['email'];
        $user->password = isset($validatedData['password']) ? Hash::make($validatedData['password']) : null;
        $user->cellPhone = $validatedData['cellPhone'];
        $user->role = 'manager';
        $user->status = 'active';

        // Salva os caminhos das imagens no banco de dados
        $user->imagedoc = $documentFrontBase64;
        $user->imagedoc_verso = $documentBackBase64;
        $user->imageself = $selfieBase64;
        $user->imagecomprovante = $proofBase64;

            $user->save();

            //dd($user->toArray());

                        //Service
             $response = $this->personalAccountService->personalRegister($user->toArray());
            $user->account_id = $response['account'];
            $user->save();

            $notification = [
                'message' => 'Gerente Criado Com Sucesso!',
                'alert-type' => 'success'
            ];

        } catch (\Exception $e) {
            // Se a criação falhar, capture a exceção e forneça uma mensagem de erro
            $notification = [
                'message' => 'Erro ao criar gerente: ' . $e->getMessage(),
                'alert-type' => 'error'
            ];
        }

        return redirect()->route('manager.index')->with($notification);
    }

    public function show($id)
    {
        $manager = User::findOrFail($id);
        return view('admin.manager.show', compact('manager'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $manager = User::findOrFail($id);
        return view('admin.manager.edit', compact('manager'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'document' => 'required|unique:users,document,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required',
            'birthdate' => 'required', // Corrigido aqui
            'address' => 'required',
            'number' => 'required',
            'state' => 'required',
            'zipcode' => 'required',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $cleanPhone = preg_replace('/[^0-9]/', '', $validatedData['phone']);

        // Atualiza apenas os campos que foram preenchidos no formulário
        $fillableFields = [
            'name',
            'document',
            'email',
            'phone',
            'birthdate',
            'address',
            'number',
            'state',
            'zipcode'
        ];

        foreach ($fillableFields as $field) {
            if (isset($validatedData[$field])) {
                $user->$field = $validatedData[$field];
            }
        }

        // Atualiza a senha se um novo valor foi fornecido
        if (isset($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->phone = $cleanPhone;

        try {
            $user->update();
            $notification = [
                'message' => 'Usuário atualizado com sucesso!',
                'alert-type' => 'success'
            ];
        } catch (\Exception $e) {
            $notification = [
                'message' => 'Erro ao atualizar usuário: ' . $e->getMessage(),
                'alert-type' => 'error'
            ];
        }

        return redirect()->route('manager.index')->with($notification);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        $notification = [
            'message' => 'Gerente Apagado Com Sucesso!',
            'alert-type' => 'success'
        ];



        return redirect()->route('manager.index')->with($notification);
    }
}

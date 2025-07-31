<?php

namespace App\Http\Controllers\Chats;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\CreateAccount;
use App\Models\CreateAcount;
use App\Models\User;
use App\Services\ContractService;
use App\Services\MyBank\Auth\BusinessAccountService;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BussinesChatController extends Controller
{
    protected $businessAccountService;
    protected $contractService;

    protected $steps = [
        'documentBusiness',
        'companyName',
        'fantasyName',
        'cnaeCode',
        'openDate',
        'simpleNational',
        'companyRevenue',
        'stateRegistration',
        'businessAddress',
        'businessZipCode',
        'businessAddressNumber',
        'businessState',
        'businessCity',
        'businessNeighborhood',
        'cardBusiness',
        'imagecontrato',
        'imagecomprovante_endereco',
        'name',
        'username',
        'nameMother',
        'gender',
        'birthdate',
        'idMaritalStatus',
        'political',
        'documentNumber',
        'identityDocument',
        'issueDate',
        'issuingAgency',
        'issuingState',
        'phoneNumber',
        'cellPhone',
        'rent',
        'address',
        'addressNumber',
        'zipCode',
        'neighborhood',
        'city',
        'state',
        'email',
        'password',
        'accept_terms',
        'image_doc',
        'image_doc_verso',
        'image_comprovante',
        'image_selfie',

    ];


    public function __construct(BusinessAccountService $businessAccountService, ContractService $contractService)
    {
        $this->contractService = $contractService;
        $this->businessAccountService = $businessAccountService;
    }
    public function playChat(Request $request, $agency_id)
    {
        $sessionId = $request->session()->getId();

        $chat = CreateAccount::updateOrCreate(
            ['session_id' => $sessionId],
            [
                'step' => $this->steps[0],
                'data' => ['agency_id' => $agency_id]
            ]
        );

        return response()->json(['reply' => $this->getPromptMessage($chat->step)]);
    }

    public function showChat(Request $request, $agency_id)
    {
        $agency = Agency::find($agency_id);
        if (!$agency) {
            return redirect()->to('/')->withErrors(['error' => 'Agência não encontrada.']);
        }
        return view('auth.chat.business', ['agency' => $agency->id]);
    }

    public function message(Request $request, $agency_id)
    {
        try {
            // Verifica agência
            $agency = Agency::find($agency_id);
            if (!$agency) {
                return response()->json(['reply' => 'Agência não encontrada.'], 400);
            }
            $sessionId = session()->getId();
            $chat = CreateAccount::firstOrCreate([
                'session_id' => $sessionId
            ], [
                'step' => $this->steps[0],
                'data' => []
            ]);
            $data = $chat->data ?? [];
            $currentStep = $chat->step;
            $message = trim((string)$request->input('message', ''));

            // No primeiro passo, registra agency_id em data
            if (!isset($data['agency_id'])) {
                $data['agency_id'] = $agency->id;
            }

            // Envio de imagem base64
            // Envio de imagem base64
            if ($request->has('image')) {
                \Log::info("Recebendo imagem na etapa: {$currentStep}");

                $etapasPermitidas = [
                    'image_doc',
                    'image_doc_verso',
                    'image_comprovante',
                    'image_selfie',
                    'cardBusiness',
                    'imagecontrato',
                    'imagecomprovante_endereco'
                ];

                if (!in_array($currentStep, $etapasPermitidas)) {
                    \Log::warning("Imagem enviada fora da etapa correta. Etapa atual: {$currentStep}");
                    return response()->json(['reply' => 'Por favor, envie as imagens somente quando solicitado.'], 200);
                }

                // Valida o base64 (aceitando imagem ou PDF)
                // Valida o base64 (aceitando imagem ou PDF)
                if (!$this->validateBase64File($request->input('image'))) {
                    \Log::error("Base64 inválido enviado na etapa: {$currentStep}");
                    return response()->json(['reply' => 'Imagem inválida, tente novamente.'], 200);
                }

                // Remove o prefixo "data:image/...;base64," ou "data:application/pdf;base64,"
                $base64Completo = $request->input('image');
                $base64Limpo = preg_replace('/^data:(image\/[a-zA-Z]+|application\/pdf);base64,/', '', $base64Completo);

                // Salva apenas o base64 limpo
                $data[$currentStep] = $base64Limpo;

                $chat->data = $data;
                $chat->step = $this->getNextStep($currentStep);
                $chat->save();

                \Log::info("Base64 salvo com sucesso. Próxima etapa: {$chat->step}");

                if ($chat->step === 'done') {
                    $this->processFinalization($data);
                    return response()->json(['reply' => 'Cadastro finalizado com sucesso! Em até <b>05 dias úteis</b> Você receberá um e-mail de boas-vindas! 🎉'], 200);
                }

                return response()->json(['reply' => "Imagem recebida. Agora, por favor, envie: " . $this->getPromptMessage($chat->step)], 200);
            }


            // Se mensagem vazia
            if ($message === '') {
                return response()->json(['reply' => 'Por favor, informe um valor.'], 200);
            }

            // Processa campo conforme etapa
            switch ($currentStep) {
                case 'documentBusiness':
                    $data['documentBusiness'] = $message;
                    break;
                case 'companyName':
                    $data['companyName'] = $message;
                    break;
                case 'fantasyName':
                    $data['fantasyName'] = $message;
                    break;
                case 'cnaeCode':
                    $data['cnaeCode'] = $message;
                    break;
                case 'simpleNational':
                    $lower = mb_strtolower($message);
                    if (in_array($lower, ['eireli'])) {
                        $data['simpleNational'] = 'EIRELI';
                    } elseif (in_array($lower, ['ei'])) {
                        $data['simpleNational'] = 'EI';
                    } elseif (in_array($lower, ['ltda'])) {
                        $data['simpleNational'] = 'LTDA';
                    } elseif (in_array($lower, ['s/a'])) {
                        $data['simpleNational'] = 'S/A';
                    } elseif (in_array($lower, ['ss'])) {
                        $data['simpleNational'] = 'SS';
                    } elseif (in_array($lower, ['slu'])) {
                        $data['simpleNational'] = 'SLU';
                    } elseif (in_array($lower, ['me'])) {
                        $data['simpleNational'] = 'ME';
                    } else {
                        return response()->json(['reply' => 'Opção inválida.'], 200);
                    }
                    break;

                case 'companyRevenue':
                    $data['companyRevenue'] = $message;
                    break;
                case 'stateRegistration':
                    $data['stateRegistration'] = $message;
                    break;
                case 'openDate':
                    $raw = preg_replace('/[^0-9]/', '', $message);
                    if (strlen($raw) !== 8) {
                        return response()->json(['reply' => 'Formato inválido. Use DD/MM/AAAA.'], 200);
                    }

                    $day = (int)substr($raw, 0, 2);
                    $month = (int)substr($raw, 2, 2);
                    $year = (int)substr($raw, 4, 4);

                    if (!checkdate($month, $day, $year)) {
                        return response()->json(['reply' => 'Data inválida. Verifique o dia, mês e ano.'], 200);
                    }

                    $formatted = sprintf('%04d-%02d-%02d', $year, $month, $day);
                    $data[$currentStep] = $formatted;
                    break;
                case 'businessZipCode':
                    $data['businessZipCode'] = $message;
                    break;
                case 'businessAddress':
                    $data['businessAddress'] = $message;
                    break;
                case 'businessState':
                    $data['businessState'] = $message;
                    break;
                case 'businessCity':
                    $data['businessCity'] = $message;
                    break;
                case 'businessNeighborhood':
                    $data['businessNeighborhood'] = $message;
                    break;
                case 'name':
                    $data['name'] = $message;
                    break;
                case 'username':
                    $data['username'] = $message;
                    break;
                case 'nameMother':
                    $data['nameMother'] = $message;
                    break;
                case 'documentNumber':
                    $cleanDocument = preg_replace('/\D/', '', $message);
                    if (User::where('documentNumber', $cleanDocument)->exists()) {
                        return response()->json(['reply' => 'Este CPF já está cadastrado.'], 200);
                    }
                    $data['documentNumber'] = $cleanDocument;
                    break;
                case 'identityDocument':
                    $cleanId = trim($message);
                    if (User::where('identityDocument', $cleanId)->exists()) {
                        return response()->json(['reply' => 'Esta identidade já está cadastrada.'], 200);
                    }
                    $data['identityDocument'] = $cleanId;
                    break;

                case 'issueDate':
                case 'birthdate':
                    $raw = preg_replace('/[^0-9]/', '', $message);
                    if (strlen($raw) !== 8) {
                        return response()->json(['reply' => 'Formato inválido. Use DD/MM/AAAA.'], 200);
                    }

                    $day = (int)substr($raw, 0, 2);
                    $month = (int)substr($raw, 2, 2);
                    $year = (int)substr($raw, 4, 4);

                    if (!checkdate($month, $day, $year)) {
                        return response()->json(['reply' => 'Data inválida. Verifique o dia, mês e ano.'], 200);
                    }

                    $formatted = sprintf('%04d-%02d-%02d', $year, $month, $day);
                    $data[$currentStep] = $formatted;
                    break;
                case 'issuingAgency':
                    $data['issuingAgency'] = $message;
                    break;
                case 'issuingState':
                    $data['issuingState'] = strtoupper(trim($message));
                    break;
                case 'gender':
                    $lower = mb_strtolower($message);
                    if (in_array($lower, ['masculino', 'm'])) {
                        $data['gender'] = 'MASCULINO';
                    } elseif (in_array($lower, ['feminino', 'f'])) {
                        $data['gender'] = 'FEMININO';
                    } elseif (in_array($lower, ['outros', 'outro', 'o'])) {
                        $data['gender'] = 'OUTROS';
                    } else {
                        return response()->json(['reply' => 'Opção inválida.'], 200);
                    }
                    break;
                case 'idMaritalStatus':
                    $lower = mb_strtolower($message);
                    if (in_array($lower, ['solteiro', 'single'])) {
                        $data['idMaritalStatus'] = 'single';
                    } elseif (in_array($lower, ['casado', 'married'])) {
                        $data['idMaritalStatus'] = 'married';
                    } elseif (in_array($lower, ['separado', 'separate'])) {
                        $data['idMaritalStatus'] = 'separate';
                    } elseif (in_array($lower, ['viuvo', 'widower'])) {
                        $data['idMaritalStatus'] = 'widower';
                    } else {
                        return response()->json(['reply' => 'Opção inválida.'], 200);
                    }
                    break;
                case 'political':
                    $lower = mb_strtolower($message);
                    if (in_array($lower, ['sim', '1'])) {
                        $data['political'] = '1';
                    } elseif (in_array($lower, ['nao', 'não', '0'])) {
                        $data['political'] = '0';
                    } else {
                        return response()->json(['reply' => 'Opção inválida.'], 200);
                    }
                    break;
                case 'phoneNumber':
                    $data['phoneNumber'] = trim($message);
                    break;
                case 'cellPhone':
                    $data['cellPhone'] = trim($message);
                    break;
                case 'rent':
                    $data['rent'] = $message;
                    break;
                case 'address':
                    $data['address'] = $message;
                    break;
                case 'addressNumber':
                    $data['addressNumber'] = $message;
                    break;
                case 'zipCode':
                    $data['zipCode'] = trim($message);
                    break;
                case 'neighborhood':
                    $data['neighborhood'] = $message;
                    break;
                case 'city':
                    $data['city'] = $message;
                    break;
                case 'state':
                    $data['state'] = strtoupper(trim($message));
                    break;
                case 'email':
                    if (User::where('email', $message)->exists()) {
                        return response()->json(['reply' => 'Email já cadastrado.'], 200);
                    }
                    $data['email'] = $message;
                    break;
                case 'password':
                    $data['password'] = $message;
                    $message = str_repeat('*', strlen($message));
                    break;
                case 'accept_terms':
                    if (!in_array(mb_strtolower($message), ['sim', 'yes'])) {
                        return response()->json(['reply' => 'Você precisa aceitar os termos.'], 200);
                    }
                    $data['accept_terms'] = true;
                    break;
                default:
                    $data[$currentStep] = $message;
                    break;
            }

            $chat->data = $data;
            $chat->step = $this->getNextStep($currentStep);
            $chat->save();

            if ($chat->step === 'done') {
                $this->processFinalization($data);
                return response()->json(['reply' => 'Cadastro finalizado!, Em Breve Você Recebera um Email de Boas Vindas.'], 200);
            }
            return response()->json(['reply' => $this->getPromptMessage($chat->step)], 200);
        } catch (\Exception $e) {
            Log::error('Erro no chat onboarding: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['reply' => 'Erro interno.'], 500);
        }
    }

    protected function processFinalization(array $data)
    {
        DB::beginTransaction();
        try {
            // Usa agency_id previamente salvo
            $user = User::create([
                'documentBusiness' => $data['documentBusiness'],
                'status' => 'inactive',
                'companyName' => $data['companyName'],
                'fantasyName' => $data['fantasyName'],
                'cnaeCode' => $data['cnaeCode'],
                'openDate' => $data['openDate'],
                'simpleNational' => $data['simpleNational'],
                'companyRevenue' => $data['companyRevenue'],
                'stateRegistration' => $data['stateRegistration'],
                'businessZipCode' => $data['businessZipCode'],
                'businessAddress' => $data['businessAddress'],
                'businessAddressNumber' => $data['businessAddressNumber'],
                'businessState' => $data['businessState'],
                'businessCity' => $data['businessCity'],
                'businessNeighborhood' => $data['businessNeighborhood'],
                'cardBusiness' => $data['cardBusiness'],
                'imagecontrato' => $data['imagecontrato'],
                'imagecomprovante_endereco' => $data['imagecomprovante_endereco'],
                'name' => $data['name'],
                'username' => $data['username'],
                'nameMother' => $data['nameMother'],
                'gender' => $data['gender'],
                'documentNumber' => $data['documentNumber'],
                'identityDocument' => $data['identityDocument'],
                'issueDate' => $data['issueDate'],
                'issuingAgency' => $data['issuingAgency'],
                'issuingState' => $data['issuingState'],
                'birthdate' => $data['birthdate'],
                'idMaritalStatus' => $data['idMaritalStatus'],
                'political' => $data['political'],
                'rent' => $data['rent'],
                'phoneNumber' => $data['phoneNumber'],
                'cellPhone' => $data['cellPhone'],
                'address' => $data['address'],
                'addressNumber' => $data['addressNumber'],
                'state' => $data['state'],
                'zipCode' => $data['zipCode'],
                'neighborhood' => $data['neighborhood'],
                'city' => $data['city'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'agency_id' => $data['agency_id'],
                'imageself' => $data['image_selfie'],
                'imagedoc' => $data['image_doc'],
                'imagedoc_verso' => $data['image_doc_verso'],
                'imagecomprovante' => $data['image_comprovante'],
            ]);
            $contract = $this->contractService->createContractForUser($user);


            $response = $this->businessAccountService->businessRegister($user->toArray());
            $user->account_id = $response['account'];
            $user->save();



            Log::info("Usuário {$user->id} criado com agência {$data['agency_id']}. Contrato ID: {$contract->id}");
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao finalizar cadastro: ' . $e->getMessage());
            throw $e;
        }
    }

    public function resetChat()
    {
        session()->forget('personal_chat_');
        return response()->json(['reply' => 'Sessão reiniciada com sucesso!']);
    }

    protected function getNextStep($currentStep)
    {
        $index = array_search($currentStep, $this->steps, true);
        if ($index === false || $index + 1 >= count($this->steps)) {
            return 'done';
        }
        return $this->steps[$index + 1];
    }

    protected function getPromptMessage($step)
    {
        $prompts = [
            'documentBusiness' => 'Digite o <b>CNPJ da Empresa  (Somente Números Sem Pontos)!</b>',
            'companyName' => 'Digite o <b>Nome da Empresa!</b>',
            'fantasyName' => 'Digite o nome<b> Nome Fantasia da Empresa!</b>',
            'cnaeCode' => 'Digite o <b>CNAE da Empresa  (Somente Números Sem Pontos)!</b>',
            'stateRegistration' => 'Digite a <b>Inscrição Estadual da sua Empresa (Se For o Caso Digite Insento)!</b>',
            'openDate' => 'Digite a <b>Data de abertura da sua Empresa  (Somente Números Sem Pontos)!</b>',
            'simpleNational' => 'Tipo de empresa <b> (Ex:LTDA, S/A, MEI,ME) </b>',
            'companyRevenue' => 'Qual o <b>Faturamento Aproximado da Empresa  (Somente Números Sem Pontos)?</b>',
            'businessZipCode' => 'Qual o <b>CEP da Sua Empresa  (Somente Números Sem Pontos)?</b>',
            'businessAddress' => 'Qual o <b>Endereço da Sua Empresa (Sem Número)?</b>',
            'businessAddressNumber' => 'Qual o <b>Número do Endereço  (Se For Sem Número Digite "00")?</b>',
            'businessState' => 'Qual o <b>Estado? (Sigla)</b>',
            'businessCity' => 'Qual a <b>Cidade?</b>',
            'businessNeighborhood' => 'Qual o <b>Bairro?</b>',
            'cardBusiness' => 'Agora Anexe o <b>Cartão CNPJ (Formato PDF)!</b>',
            'imagecontrato' => 'Anexe o <b>Contrato Social (Formato PDF)!</b>',
            'imagecomprovante_endereco' => 'Anexe um <b>Comprovante de Endereço da Empresa (Imagem, PNG ou JPG)!</b>',
            'name' => 'Por favor, informe o <b>Nome Completo do Sócio!</b>',
            'username' => 'Como <b>Gostaria de Ser Chamado(a)?</b>',
            'nameMother' => 'Qual o <b>Nome da Sua Mãe?</b>',
            'documentNumber' => 'Informe o <b> Número de Seu CPF (Somente Números Sem Pontos)!</b>',
            'identityDocument' => 'Informe o <b>Número do Seu Documento de Identidade ou CNH  (Somente Números Sem Pontos)!</b>',
            'issueDate' => 'Informe a <b>Data de Emissão de seu Documento de Identificação  (Somente Números Sem Pontos)!</b>',
            'issuingAgency' => 'Informe o <b>Órgão Emissor de seu Documento de Identificação!</b>',
            'issuingState' => 'Informe o <b>Estado Emissor de seu Documento de Identificação (Sigla do Estado)!</b>',
            'gender' => 'Informe seu sexo: <b>Masculino, Feminino ou Outros!</b>',
            'idMaritalStatus' => 'Informe seu Estado Civil: <b>Solteiro, Casado, Separado ou Viúvo!</b>',
            'political' => 'Você é PEP (Pessoal Politicamente Exposta)? Responda <b>Sim ou Não.</b>',
            'phoneNumber' => 'Informe um <b>Número de Celular  (Somente Números Sem Pontos)!</b>',
            'cellPhone' => 'Informe seu <b>Número de WhatsApp  (Somente Números Sem Pontos)!</b>',
            'rent' => 'Informe sua <b>Renda Mensal Aproximada  (Somente Números Sem Pontos)!</b>',
            'birthdate' => 'Informe sua <b>Data de Nascimento  (Somente Números Sem Pontos)!</b>',
            'address' => 'Informe seu <b>Endereço Completo(Sem o Número)!</b>',
            'addressNumber' => 'Informe  o Número <b> Sua Residência (Se For Sem Número Digite "00")</b>',
            'zipCode' => 'Informe o <b>CEP de Sua Residência  (Somente Números Sem Pontos)!</b>',
            'neighborhood' => 'Informe o <b>Bairro de Sua Residência!</b>',
            'city' => 'Informe a <b> Cidade!</b>',
            'state' => 'Informe o <b>Estado (Sigla do Estado)!</b>',
            'email' => 'Informe seu <b>Email de Acesso!</b>',
            'password' => 'Crie Sua <b>Senha de Acesso!</b>',
            'accept_terms' => 'Você Concorda com <b> Os Termos de Uso? Sim ou Não!</b>',
            'image_doc' => 'Agora <b>Envie uma Imagem da Frente de seu Documento de Identificação (Imagem, PNG ou JPG)!</b>',
            'image_doc_verso' => 'Envie uma <b>Imagem do Verso de seu Documento de Identificação (Imagem, PNG ou JPG)!</b>',
            'image_comprovante' => 'Envie um <b>Comprovante de Endereço do Sócio (Imagem, PNG ou JPG)!</b>',
            'image_selfie' => 'Envie uma <b>Selfie Segurando o Documento de Identificação (Imagem, PNG ou JPG)!</b>',
        ];
        return $prompts[$step] ?? 'Informe o próximo dado.';
    }

    protected function validateBase64File($base64)
    {
        return preg_match('/^data:(image\/(\w+)|application\/pdf);base64,/', $base64);
    }

    protected function uploadBase64($base64, $prefix)
    {
        if (!$base64) {
            \Log::warning('Base64 vazio recebido no upload.');
            return null;
        }

        // Limite do tamanho da string base64 bruta (~33% maior que o arquivo real)
       $maxBase64Size = 100 * 1024 * 1024; // Aproximadamente suficiente para arquivos reais de até 10MB

        if (strlen($base64) > $maxBase64Size) {
            \Log::warning('Base64 excede o tamanho máximo permitido.', [
                'tamanho_base64' => strlen($base64),
                'prefixo' => $prefix
            ]);
            return null;
        }

        if (preg_match('/^data:(.*);base64,/', $base64, $matches)) {
            $mimeType = $matches[1];

            \Log::info('Base64 identificado.', [
                'mimeType' => $mimeType,
                'prefixo' => $prefix
            ]);

            $data = substr($base64, strpos($base64, ',') + 1);
            $data = base64_decode($data);

            if ($data === false) {
                \Log::error('Falha ao decodificar base64.', [
                    'prefixo' => $prefix
                ]);
                return null;
            }

            // Limite de tamanho real do arquivo após decode: 10MB
            $maxSizeBytes = 15 * 1024 * 1024;
            if (strlen($data) > $maxSizeBytes) {
                \Log::warning('Arquivo decodificado excede 10MB.', [
                    'tamanho_bytes' => strlen($data),
                    'prefixo' => $prefix
                ]);
                return null;
            }

            switch ($mimeType) {
                case 'image/jpeg':
                case 'image/jpg':
                    $extension = 'jpg';
                    break;
                case 'image/png':
                    $extension = 'png';
                    break;
                case 'image/gif':
                    $extension = 'gif';
                    break;
                case 'application/pdf':
                    $extension = 'pdf';
                    break;
                default:
                    \Log::warning('Tipo de arquivo não permitido.', [
                        'mimeType' => $mimeType
                    ]);
                    return null;
            }

            $filename = $prefix . '_' . time() . '.' . $extension;

            try {
                Storage::disk('public')->put($filename, $data);
                \Log::info('Arquivo salvo com sucesso.', [
                    'filename' => $filename,
                    'tamanho_bytes' => strlen($data)
                ]);
            } catch (\Exception $e) {
                \Log::error('Erro ao salvar arquivo.', [
                    'erro' => $e->getMessage()
                ]);
                return null;
            }

            return $filename;
        }

        \Log::warning('Base64 inválido ou em formato incorreto.');
        return null;
    }
}

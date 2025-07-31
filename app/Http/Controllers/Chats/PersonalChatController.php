<?php

namespace App\Http\Controllers\Chats;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\CreateAccount;
use App\Models\CreateAcount;
use Illuminate\Http\Request;
use App\Models\PersonalAccount;
use App\Models\User;
use App\Services\ContractService;
use App\Services\MyBank\Auth\PersonalAccountService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class PersonalChatController extends Controller
{
    protected $personalAccountService;
    protected $contractService;

    protected $steps = [
        'name',
        'username',
        'nameMother',
        'documentNumber',
        'identityDocument',
        'issueDate',
        'issuingAgency',
        'issuingState',
        'profession',
        'gender',
        'idMaritalStatus',
        'political',
        'phoneNumber',
        'cellPhone',
        'birthdate',
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


    public function __construct(PersonalAccountService $personalAccountService, ContractService $contractService)
    {
        $this->contractService = $contractService;
        $this->personalAccountService = $personalAccountService;
    }

    public function startChat(Request $request, $agency_id)
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
        return view('auth.chat.personal', ['agency' => $agency->id]);
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
            if ($request->has('image')) {
                Log::info("Recebendo imagem na etapa: {$currentStep}");

                if (!in_array($currentStep, ['image_doc', 'image_doc_verso', 'image_comprovante', 'image_selfie'])) {
                    Log::warning("Imagem enviada fora da etapa correta. Etapa atual: {$currentStep}");
                    return response()->json(['reply' => 'Por favor, envie as imagens somente quando solicitado.'], 200);
                }

                // Aqui você apenas valida o base64, sem salvar no disco
                if (!$this->validateBase64Image($request->input('image'))) {
                    Log::error("Base64 inválido enviado na etapa: {$currentStep}");
                    return response()->json(['reply' => 'Imagem inválida, tente novamente.'], 200);
                }

                // Salva o base64 no banco (no campo correto)
                $data[$currentStep] = $request->input('image');

                $chat->data = $data;
                $chat->step = $this->getNextStep($currentStep);
                $chat->save();

                Log::info("Base64 salvo com sucesso. Próxima etapa: {$chat->step}");

                if ($chat->step === 'done') {
                    Log::info("Todas as etapas concluídas. Iniciando finalização.");
                    $this->processFinalization($data);
                    return response()->json(['reply' => 'Cadastro finalizado com sucesso!, Em até <b>05 dias úteis</b> Você Receberá um Email de Boas Vindas! 🎉'], 200);
                }

                return response()->json(['reply' => "Imagem recebida. Agora, por favor, envie: " . $this->getPromptMessage($chat->step)], 200);
            }



            // Se mensagem vazia
            if ($message === '') {
                return response()->json(['reply' => 'Por favor, informe um valor.'], 200);
            }

            // Processa campo conforme etapa
            switch ($currentStep) {
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
                        $prompt = $currentStep === 'issueDate'
                            ? 'Formato inválido. Informe DD/MM/AAAA.'
                            : 'Formato inválido. Informe DD/MM/AAAA.';
                        return response()->json(['reply' => $prompt], 200);
                    }
                    $day = (int)substr($raw, 0, 2);
                    $month = (int)substr($raw, 2, 2);
                    $year = (int)substr($raw, 4, 4);
                    if (!checkdate($month, $day, $year)) {
                        $prompt = $currentStep === 'issueDate'
                            ? 'Data inválida. Informe uma data válida.'
                            : 'Data inválida. Informe uma data válida.';
                        return response()->json(['reply' => $prompt], 200);
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
                case 'profession':
                    $data['profession'] = $message;
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
                'status' => 'inactive',
                'name' => $data['name'],
                'username' => $data['username'],
                'nameMother' => $data['nameMother'],
                'documentNumber' => $data['documentNumber'],
                'identityDocument' => $data['identityDocument'],
                'issueDate' => $data['issueDate'],
                'issuingAgency' => $data['issuingAgency'],
                'issuingState' => $data['issuingState'],
                'profession' => $data['profession'] ?? null,
                'birthdate' => $data['birthdate'],
                'gender' => $data['gender'],
                'idMaritalStatus' => $data['idMaritalStatus'],
                'political' => $data['political'],
                'phoneNumber' => $data['phoneNumber'],
                'cellPhone' => $data['cellPhone'],
                'rent' => $data['rent'] ?? null,
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

            $response = $this->personalAccountService->personalRegister($user->toArray());
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

    public function resetChat(Request $request)
    {
        $sessionId = $request->session()->getId();
        CreateAccount::where('session_id', $sessionId)->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->regenerate();

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
            'name' => 'Por favor, informe seu <b>Nome Completo!</b>',
            'username' => 'Como gostaria de ser <b>Chamado(a)?</b>',
            'nameMother' => 'Qual o nome da sua <b>Mãe?</b>',
            'documentNumber' => 'Informe seu <b>CPF (Somente Números Sem Pontos)!</b> ',
            'identityDocument' => 'Informe o <b>Número do seu Documento de Identidade ou CNH (Somente Números Sem Pontos)!</b>',
            'issueDate' => 'Informe a <b>Data de Emissão do Documento de Identificação (Somente Números Sem Pontos)!</b>',
            'issuingAgency' => 'Informe o <b> Órgão Emissor do Documento de Identificação!</b>',
            'issuingState' => 'Informe o <b>Estado Emissor do Documento de Identificação (Sigla do Estado)!</b>',
            'profession' => 'Informe <b>Sua Profissão!</b>',
            'gender' => 'Informe seu sexo: <b>Masculino, Feminino ou Outros!</b>',
            'idMaritalStatus' => 'Informe seu Estado Civil: <b>Solteiro, Casado, Separado ou Viúvo!</b>',
            'political' => 'Você é PEP? (Pessoal Politicamente  Exposta) Responda <b>Sim ou Não!</b>',
            'phoneNumber' => 'Informe o <b>Seu Número Celular (Somente Números Sem Pontos)!</b>',
            'cellPhone' => 'Informe  seu <b>WhatsApp (Somente Números Sem Pontos)!</b>',
            'birthdate' => 'Informe sua <b>Data de Nascimento!</b>',
            'rent' => 'Informe sua <b>Renda Mensal (Aproximada) (Somente Números Sem Pontos)!</b>',
            'address' => 'Informe  Seu <b>Endereço Completo(Sem o Número)!</b>',
            'addressNumber' => 'Informe o <b>Número da Residência (Se For Sem Número Digite "00")!</b>',
            'zipCode' => 'Informe o <b>CEP da Residência (Somente Números Sem Pontos)!</b>',
            'neighborhood' => 'Informe o <b>Bairro da Residência!</b>',
            'city' => 'Informe  a <b>Cidade!</b>',
            'state' => 'Informe o <b>Estado (sigla)!</b>',
            'email' => 'Informe <b>Seu Email de Acesso!</b>',
            'password' => '<b>Crie Uma senha.</b>',
            'accept_terms' => '<b>Você Concorda Com Os Termos de Uso? Sim ou Não!</b>',
            'image_doc' => '<label><b> Agora Envie uma Imagem da Frente de Seu Documento de Identificação (Imagem, PNG ou JPG)!</b></label>',
            'image_doc_verso' => '<label><b>Envie uma Imagem do Verso de Seu Documento de Identificação (Imagem, PNG ou JPG)!</b></label>',
            'image_comprovante' => '<label><b>Envie um Comprovante de Endereço (Imagem, PNG ou JPG)!</b></label>',
            'image_selfie' => '<label><b>Envie uma selfie com o documento!</b></label>',
        ];
        return $prompts[$step] ?? 'Informe o próximo dado.';
    }
    protected function validateBase64Image($base64)
    {
        return base64_decode($base64, true) !== false;
    }
    protected function uploadBase64($base64, $prefix)
    {
        if (!$base64) {
            Log::error("Base64 vazio ao tentar salvar imagem.");
            return null;
        }


        try {
            // Extrai o tipo e os dados do base64 (ex: data:image/png;base64,...)
            if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
                $base64 = substr($base64, strpos($base64, ',') + 1);
                $extension = strtolower($type[1]); // jpg, png, gif etc.
                if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                    Log::error("Extensão de imagem inválida: {$extension}");
                    return null;
                }
            } else {
                Log::error("Formato de base64 inválido.");
                return null;
            }

            $base64 = str_replace(' ', '+', $base64);
            $imageData = base64_decode($base64);

            if ($imageData === false) {
                Log::error("Falha ao decodificar base64.");
                return null;
            }

            $filename = $prefix . '_' . time() . '.' . $extension;
            $path = public_path('uploads/' . $filename);

            if (!file_exists(public_path('uploads'))) {
                mkdir(public_path('uploads'), 0755, true);
            }

            file_put_contents($path, $imageData);

            Log::info("Imagem salva com sucesso em: uploads/{$filename}");

            // Retorna apenas o caminho relativo para salvar no banco
            return 'uploads/' . $filename;
        } catch (\Exception $e) {
            Log::error("Erro ao salvar imagem base64: " . $e->getMessage());
            return null;
        }
    }
}

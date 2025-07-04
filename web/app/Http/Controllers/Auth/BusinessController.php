<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\MyBank\Auth\BusinessAccountService;
use App\Models\Agency;
use App\Models\OpenContract;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Str;
use Illuminate\Support\Facades\DB;
use Image;
use App\Models\Split;
use App\Models\UserServices;
use Illuminate\Support\Facades\File;


class BusinessController extends Controller
{
   protected $businessAcountService;
   public function __construct(BusinessAccountService $businessAcountService)
   {
    $this->businessAcountService = $businessAcountService;
   }
public function create(Request $request, $agency_id)
    {
        $agency = Agency::find($agency_id);
        if (!$agency) {
            return redirect()->to('/')->withErrors(['error' => 'Agência não encontrada.']);
        }
        return view('auth.business', ['agency' => $agency->id]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
public function businessRegister(Request $request)
{
    //dd($request->all());
    $request->validate([

        //Personal Data
       'name' => ['required', 'string', 'max:255'],
        'username' => ['required', 'string', 'max:255'],
        'nameMother' => ['required', 'string', 'max:255'],
        'gender' => ['required'],
        'idMaritalStatus' => ['required'],
        'political' => ['required'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        'documentNumber' => ['required', 'string', 'max:255', 'unique:users,documentNumber'],
        'identityDocument' => 'required|unique:users,identityDocument',
        'issueDate' => 'required',
        'issuingAgency' => 'required',
        'issuingState' => 'required',
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
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'imageself' => 'required|string',
        'imagedoc' => 'required|string',
        'imagedoc_verso' => 'required|string',
        'imagecomprovante' => 'required|string',

        //Business Data
        'companyName' => ['required', 'string', 'max:255'],
        'fantasyName' => ['required', 'string', 'max:255'],
        'documentBusiness' => ['required','string'],
        'cnaeCode' =>  'required',
        'openDate' => 'required',
        'simpleNational'=> 'required',
        'companyRevenue'=> 'required',
        'stateRegistration' => 'required',
        'businessZipCode' => 'required',
        'businessAddressNumber' => 'required',
        'businnesAddress' => 'required',
        'businessState' => 'required',
        'businessCity' => 'required',
        'businessNeighborhood' => 'required',
        'cardBusiness' => 'required|string',
        'imagecontrato' => 'required|string',
        'imagecomprovante_endereco' => 'required|string',


    ]);

    DB::beginTransaction();

   try {
    // Função auxiliar para salvar arquivos base64
    function saveBase64File($base64File, $folderPath, $fileName)
    {
        if (str_contains($base64File, 'data:image/png;base64,')) {
            $base64File = str_replace('data:image/png;base64,', '', $base64File);
            $extension = 'png';
        } elseif (str_contains($base64File, 'data:image/jpeg;base64,')) {
            $base64File = str_replace('data:image/jpeg;base64,', '', $base64File);
            $extension = 'jpg';
        } elseif (str_contains($base64File, 'data:image/jpg;base64,')) {
            $base64File = str_replace('data:image/jpg;base64,', '', $base64File);
            $extension = 'jpg';
        } elseif (str_contains($base64File, 'data:application/pdf;base64,')) {
            $base64File = str_replace('data:application/pdf;base64,', '', $base64File);
            $extension = 'pdf';
        } else {
            // Default fallback
            $extension = 'jpg';
        }

        $base64File = str_replace(' ', '+', $base64File); // Corrige espaços
        $fileNameWithExt = $fileName . '-' . time() . '.' . $extension;
        $filePath = public_path($folderPath);

        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
        }

        \File::put($filePath . $fileNameWithExt, base64_decode($base64File));

        \Log::info('Arquivo salvo: ' . $fileNameWithExt);
        return $fileNameWithExt;
    }

    // Diretório padrão dos arquivos
    $folderPath = '/uploads/business/documents/';

    // Salvar imagens base64
    $imageself = saveBase64File($request->imageself, $folderPath, 'imageself');
    $imagedoc = saveBase64File($request->imagedoc, $folderPath, 'imagedoc');
    $imagedoc_verso = saveBase64File($request->imagedoc_verso, $folderPath, 'imagedoc_verso');
    $imagecomprovante = saveBase64File($request->imagecomprovante, $folderPath, 'imagecomprovante');
    $cardBusiness = saveBase64File($request->cardBusiness, $folderPath, 'cardBusiness');
    $imagecontrato = saveBase64File($request->imagecontrato, $folderPath, 'imagecontrato');
    $imagecomprovante_endereco = saveBase64File($request->imagecomprovante_endereco, $folderPath, 'imagecomprovante_endereco');

    // Verifica se agência existe
    $agency = Agency::find($request->input('agency_id'));
    if (!$agency) {
        return redirect()->to('/')->withErrors(['error' => 'Agência não encontrada.']);
    }

    // Conversão da renda
    $rent = 0.00;
    if (!empty($request->rent)) {
        $rent = convertAmountToInt($request->rent) / 100;
    }

        $cleanDocument = preg_replace('/[^0-9]/', '', $request->documentNumber);
        $cleanBusinessDocument = preg_replace('/[^0-9]/', '', $request->documentBusiness);
        $cleanPhone = preg_replace('/[^0-9]/', '', $request->phoneNumber);

        // Criação do usuário
        $user = User::create([
            //User Create
           'status' => 'active',
            'name' => $request->name,
            'username' => $request->username,
            'nameMother' => $request->nameMother,
            'documentNumber' => $cleanDocument,
            'identityDocument' => $request->identityDocument,
            'issueDate' => $request->issueDate,
            'issuingAgency' => $request->issuingAgency,
            'issuingState' => $request->issuingState,
            'gender' => $request->gender,
            'idMaritalStatus' => $request->idMaritalStatus,
            'political' => $request->political,
            'phoneNumber' =>  $cleanPhone,
            'cellPhone' => $request->cellPhone,
            'birthdate' => $request->birthdate,
            'rent' => $rent,
            'address' => $request->address,
            'addressNumber' => $request->addressNumber,
            'zipCode' => $request->zipCode,
            'neighborhood' => $request->neighborhood,
            'city' => $request->city,
            'complement' => $request->complement,
            'state' => $request->state,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'agency_id' => $agency->id,
            'imageself' => $imageself,
            'imagedoc' => $imagedoc,
            'imagedoc_verso' => $imagedoc_verso,
            'imagecomprovante' => $imagecomprovante,

            //Business Data
            'companyName' => $request->companyName,
            'fantasyName' =>$request->fantasyName,
            'documentBusiness' =>  $cleanBusinessDocument,
            'cnaeCode' => $request->cnaeCode,
            'openDate' => $request->openDate,
            'simpleNational' => $request->simpleNational,
            'companyRevenue' => $request->companyRevenue,
            'stateRegistration' => $request->stateRegistration,
            'businessZipCode' => $request->businessZipCode,
            'businnesAddress' => $request->businnesAddress,
            'businessAddressNumber' => $request->businessAddressNumber,
            'businessState' => $request->businessState,
            'businessCity' => $request->businessCity,
            'businessNeighborhood' => $request->businessNeighborhood,
            'cardBusiness' => $cardBusiness,
            'imagecontrato' => $imagecontrato,
            'imagecomprovante_endereco' => $imagecomprovante_endereco,


        ]);

            //Label Gender
            $genderLabel = '';
            if ($user->gender === 'Masculino') {
                $genderLabel = 'Masculino';
            } elseif ($user->gender === 'Feminino') {
                $genderLabel = 'Feminino';
            } else {
                $genderLabel = 'OUTROS';
            }

            $ralbank = Split::where('title', 'like', '%Polocal%')->first();
            if ($ralbank) {
                UserServices::create([
                    'status' => 'active',
                    'user_id' => $user->id,
                    'payment_id' => $ralbank->id,
                ]);
            }


            //Criação de contrato
            $incomeFormatted = number_format($user->rent, 2, ',', '.'); // Formata para duas casas decimais, separando os milhares por ponto e a vírgula como separador decimal

            $contractContent = "<img src='/images/logo.svg' alt='Cabeçalho do Contrato'>\n";
            $contractContent .= "<b>CONTRATO DE ABERTURA DE CONTA</b><br><br>";
            $contractContent .= "<b>Nome:</b> {$user->name}<br>";
            $contractContent .= "<b>CPF:</b> {$user->documentNumber}<br>";
            $contractContent .= "<b>Data de Nascimento:</b> {$user->birthdate}<br>";
            $contractContent .= "<b>Sexo:</b> {$genderLabel}<br>";
            $contractContent .= "<b>Logradouro:</b> {$user->address}<br>";
            $contractContent .= "<b>Número:</b> {$user->number}<br>";
            $contractContent .= "<b>Complemento:</b> {$user->complement}<br>";
            $contractContent .= "<b>Bairro:</b> {$user->neighborhood}<br>";
            $contractContent .= "<b>Cidade:</b> {$user->city}<br>";
            $contractContent .= "<b>CEP:</b> {$user->zipCode}<br>";
            $contractContent .= "<b>Telefone:</b> {$user->phoneNumber}<br>";
            $contractContent .= "<b>Email:</b> {$user->email}<br>";
            $contractContent .= "<b>Renda Mensal:</b> R$ {$incomeFormatted}<br><br>";

            // Cláusula 1: Pessoas Politicamente Expostas
            $contractContent .= "<b>PESSOAS POLITICAMENTE EXPOSTAS</b><br>";
            $contractContent .= "<p style='text-align: justify;'>
        Conforme estabelece a Circular 3.461 do Banco Central do Brasil, de 24 de julho de 2009, as
        Instituições Financeiras devem identificar pessoas que desempenham ou tenham desempenhado,
        nos últimos 5 (cinco) anos, no Brasil ou em outros países, cargos, empregos ou funções públicas
        relevantes, assim como seus representantes, familiares e pessoas de seu relacionamento
        Exerce ou exerceu nos últimos cinco anos, algum cargo, emprego ou função pública relevante?
        ( ) Sim ( ) Não
        Possui relacionamento/ligação com Agente Público? ( ) Sim ( ) Não
        </p><br>";

            // Cláusula 2: Declaração do Cliente
            $contractContent .= "<b>DECLARAÇÃO DO CLIENTE</b><br>";
            $contractContent .= "<p style='text-align: justify;'>
        Declaro que as informações acima são a expressão da verdade, responsabilizando-me por elas, sob
        pena de aplicação do dispositivo nº 64 da Lei 8.383, de 30 de dezembro de 1991. Autorizo o Banco
        POLOCAL BANK, sediado em Q C11 LT 08 SL 202 SN - BRASILIA, TAGUATINGA - DF, por seus
        prepostos, a consultar as informações consolidadas da empresa no Sistema de Informações de
        Crédito do Banco Central do Brasil - SCR, bem como dos sócios e demais empresas pertencentes
        ao mesmo Grupo Econômico e/ou que tenham participação acionária direta ou indireta dos
        sócios/acionistas da empresa acima citada, referente ao risco em operações de crédito,
        coobrigações e todas as demais informações fornecidas pelo SCR.
        Em consonância com o que dispõe a Circular 3.461 do Banco Central do Brasil e a Instrução 301 da
        Comissão de Valores mobiliários, declaro que pretendo manter relação de negócios com o Grupo
        POLOCAL de natureza financeira e com o seguinte propósito:
        ( )Captação de Recursos ( ) Empresarial ( ) Câmbio ( ) DTVM ( ) Incentivo ( ) Cultural Conta
        ( )Corrente Adquirência ( ) Conta Escrow ( )Empresarial / Crédito ( )Outros produtos________

        Nos termos da Instrução 301 da Comissão de Valores Mobiliários, o cliente acima qualificado
        declara ser “Pessoa vinculada” à POLOCAL BANK Distribuidora de Títulos e Valores Mobiliários? De
        acordo com a Instrução 505/11 da CVM pessoas vinculadas (acionistas, cônjuges e filhos até 18
        anos) não ficam impedidas de investir no mercado de capitais, mas devem realizar suas aplicações
        exclusivamente pela nossa plataforma. ( ) Sim ( ) Não
        Conforme se verifica na legislação Norte-Americana denominada FATCA, consideram-se “US
        Person”: cidadão norte-americano, titular de green card, proprietário de ativos nos E.U.A. e
        territórios, cidadão que por razão de período de viagens aos Estados Unidos da América ou
        qualquer outra razão seja passível de tributação pela receita federal norte-americana (IRS). Neste
        contexto, o cliente acima qualificado declara se enquadrar na condição de “US Person”? ( ) Sim
        ( ) Não
        Declaro, nos termos da Instrução 301 da Comissão de Valores Mobiliários, que: (Campo exclusivo
        para clientes DTVM) (I) Não estou impedido de operar no mercado de valores mobiliários;
        (II) Minhas ordens devem ser transmitidas por escrito, por sistemas eletrônicos de conexões
        automatizadas ou telefone e outros sistemas de transmissão de voz;
        (III) Autorizo a POLOCAL., caso existam débitos pendentes em meu nome, a liquidar os contratos,
        direitos e ativos adquiridos por sua conta e ordem, bem como a executar bens e direitos dados em
        garantia de minhas operações ou que estejam em poder da POLOCAL BANK, aplicando o produto
        da venda no pagamento dos débitos pendentes, independentemente de notificação judicial ou
        extrajudicial.

        <b>Beneficiário final:</b> Toda pessoa natural que, de maneira direta ou indireta, controla ou influencia
        nas decisões da empresa, independente da natureza do vínculo. Adicionalmente, considera-se
        beneficiário final toda pessoa natural que possua 25% (ou mais) de participação societária. Em
        caso de sócio Pessoa Jurídica, este deverá abrir a composição de toda a cadeia societária até
        alcançar a(s) pessoa(s) física(s) caracterizada(s) como beneficiário(s) final(is).
        Assumo o compromisso de comunicar expressa e imediatamente ao BANCO qualquer alteração
        nas declarações e informações aqui prestadas, bem como, a atender os procedimentos
        necessários para atualização de meu cadastro nesta instituição.

        </p><br>";

            // Cláusula 2: Declaração do Cliente
            $contractContent .= "<center><b>CONTRATO DE ABERTURA DE CONTA CORRENTE</b></center><br><br>";
            $contractContent .= "<p style='text-align: justify;'>
        <b>BANCO POLOCAL BANK</b>, com sede em Q C11 LT 08 SL 202 SN - BRASILIA, TAGUATINGA - DF,
        inscrito no CNPJ/MF sob o nº 17.300.093/0001-72, adiante denominado <b>“BANCO”</b> e a(s) pessoa(s)
        qualificada(s) na Ficha Cadastral, a seguir designado como <b>“CORRENTISTA”<b>, têm entre si justo e
        contratado o presente Contrato de abertura de conta corrente bancária (o “Contrato”), que se
        regerá pelas seguintes cláusulas e condições:

        </p><br>";
            $contractContent .= "<b>1. DA FINALIDADE DO CONTRATO</b><br>";
            $contractContent .= "<p style='text-align: justify;'>
        1.1. Serão regidas por este Contrato: a abertura, manutenção e movimentação de conta
        corrente bancária, a qual registrará créditos e débitos entre o BANCO e o CORRENTISTA,
        que sejam exigíveis à vista, todas vinculadas à conta corrente, de acordo com as opções e
        os dados constantes na Ficha Cadastral.

        1.2. Não se submetem a este Contrato as operações que não se vinculem à conta corrente,
        bem como créditos e débitos relativos a depósitos a prazo e a todos aqueles que não sejam
        exigíveis à vista.
        </p><br>";

            $contractContent .= "<b>2. DA ADESÃO AO CONTRATO</b><br>";
            $contractContent .= "<p style='text-align: justify;'>
        2.1. A adesão a este Contrato será realizada por qualquer dos meios admitidos em direito,
        em especial por meio de aceitação pelo BANCO da Ficha Cadastral, preenchida pelo
        CORRENTISTA, depois de devidamente analisada, e, ainda aceitação dos termos aqui
        consignados pelo CORRENTISTA, mediante o depósito de valores entregues e conferido e
        aceite pelo BANCO.
        2.2. O CORRENTISTA se compromete a comunicar imediatamente ao BANCO toda e
        qualquer alteração das informações cadastrais por ele prestadas no momento do
        preenchimento da Ficha Cadastral, principalmente as referentes à procuração e situação
        fiscal e patrimonial, sob pena de se responsabilizar por tal omissão.
        2.3. No momento da abertura da Conta Corrente, nos termos da resolução aplicável,
        solicitamos ao CORRENTISTA e às pessoas autorizadas a movimentar esta conta, os
        respectivos documentos de identificação. Esse procedimento é de extrema importância
        para garantirmos que apenas as pessoas autorizadas possam acessar a sua Conta Corrente.
        2.4. Os dados cadastrais exigidos no momento da abertura e manutenção da sua conta
        estão em conformidade com as regulamentações aplicáveis ao BANCO.
        </p><br>";

            $contractContent .= "<b>3. DA ABERTURA DA CONTA CORRENTE</b><br>";
            $contractContent .= "<p style='text-align: justify;'>
        3.1. Com a adesão a esse Contrato, o BANCO abrirá e manterá em seus sistemas conta
        corrente em nome do CORRENTISTA, utilizando-se, para tanto, dos dados cadastrais
        constantes da Ficha Cadastral, de acordo com os comprovantes entregues pelo
        CORRENTISTA, conforme exigido pela regulamentação aplicável a contas corrente de
        depósitos à vista. A conta corrente será escriturada junto à agência do BANCO

        3.2. É de responsabilidade do CORRENTISTA manter todas as informações cadastrais
        atualizadas e comunicar ao BANCO sempre que houver qualquer alteração cadastral que
        possa impactar na movimentação da conta corrente.

        3.3. O BANCO poderá solicitar ao CORRENTISTA periodicamente o envio dos documentos
        atualizados para garantir a segurança na movimentação da conta corrente.
        </p><br>";

            $contractContent .= "<b>4. DA MOVIMENTAÇÃO DA CONTA CORRENTE</b><br>";
            $contractContent .= "<p style='text-align: justify;'>
        4.1. O BANCO movimentará a conta corrente aberta nos termos deste Contrato, nela
        efetuando lançamentos a crédito e a débito. Sobre os valores depositados não incidirá
        remuneração de qualquer natureza.

        4.2. Na conta corrente serão lançados a crédito todos os valores cujo pagamento poderá
        ser reclamado pelo CORRENTISTA, tais como: (i) montantes relativos aos depósitos à vista;

        (ii) valores pagos ao BANCO por terceiros e destinados ao CORRENTISTA, tais como ordens
        de pagamento (inclusive cheques), transferências de recursos enviadas em seu benefício,
        inclusive Transferências Eletrônicas Disponíveis – TED e Documentos de Ordem de Crédito
        – DOC; e (iii) valores devidos pelo BANCO ao CORRENTISTA, com base em qualquer relação
        jurídica entre eles mantida, tais como empréstimos concedidos, exceto no caso de previsão
        expressa em contrário na respectiva operação de crédito.

        4.3. Na conta corrente serão lançados a débito todos os valores cujo pagamento o BANCO
        poderá reclamar junto ao CORRENTISTA, tais como: (i) valores devidos pelo CORRENTISTA
        ao BANCO, com base em qualquer relação jurídica entre eles mantida, tais como
        empréstimos e serviços contratados; (ii) ordens de pagamento sacadas pelo CORRENTISTA
        contra o BANCO em favor de terceiros, inclusive por meio de cheques, Transferências
        Eletrônicas Disponíveis – TED e Documentos de Ordem de Crédito – DOC; e (iii) pagamentos
        efetuados pelo BANCO em favor do CORRENTISTA, inclusive restituição de recursos
        depositados (saques).

        4.4. Será permitida a movimentação da conta corrente por procurador do CORRENTISTA,
        desde que exiba o devido instrumento de procuração com a outorga de poderes específicos
        para movimentação da conta corrente. Para fins de segurança, o instrumento de
        procuração, apenas será aceito se tiver sido outorgado há, no máximo, 12 (doze) meses.

        4.5. A procuração, por instrumento público ou particular, referente à movimentação da
        conta corrente, recebida pelo BANCO, somente será considerada revogada ou cancelada,
        para todos os efeitos, a partir do recebimento de comunicação escrita nesse sentido, sendo
        que tal revogação somente produzirá efeitos após a confirmação pelo BANCO do
        recebimento de comunicação enviada pelo CORRENTISTA.

        4.6. O CORRENTISTA autoriza o BANCO a realizar estornos necessários para corrigir
        lançamentos indevidos, decorrentes de erros operacionais de qualquer modalidade.

        4.7. Nos termos da regulamentação aplicável, os limites de saldo mantido em conta e
        aporte de recursos, serão definidos de acordo com a capacidade financeira do
        CORRENTISTA.

        4.8. O BANCO adota programas de prevenção a lavagem de dinheiro e combate ao
        financiamento do terrorismo. Em razão disso, todas as movimentações realizadas em sua
        Conta Corrente, serão monitoradas constantemente e estarão sujeitas à requisição de
        esclarecimentos e/ou documentos adicionais. Nesta hipótese, você desde já se
        compromete a prestar os esclarecimentos necessários e/ou fornecer os documentos
        comprobatórios complementares solicitados pelo Banco POLOCAL BANK. O
        descumprimento das obrigações aqui previstas, poderá ensejar no bloqueio cautelar e/ou
        encerramento de sua conta.
        </p><br>";


            $contractContent .= "<b>5. COMPENSAÇÃO RECÍPROCA DE CRÉDITOS E DÉBITOS</b><br>";
            $contractContent .= "<p style='text-align: justify;'>
        5.1. Os lançamentos a crédito e a débito na conta corrente compensar-se-ão
        reciprocamente, a todo tempo. A compensação, uma vez acontecida, extinguirá os créditos
        e débitos do CORRENTISTA perante o BANCO até a respectiva concorrência dos valores.

        5.2. Dentre os valores lançados em conta corrente compensar-se-ão prioritariamente os
        lançamentos efetuados há mais tempo.
        </p><br>";

            $contractContent .= "<b>6. DA CONTRATAÇÃO DE PACOTE DE SERVIÇOS</b><br>";
            $contractContent .= "<p style='text-align: justify;'>
        6.1. O CORRENTISTA possui a faculdade de optar, sem a necessidade de adesão ou
        contratação especifica de pacote de serviços, pela utilização de serviços e pagamento de
        tarifas individualizados, além daqueles serviços gratuitos previstos no regulamento vigente,
        conforme determinação da Resolução do BACEN 4.196/2013.
        </p><br>";

            $contractContent .= "<b>7. OPERAÇÕES A DESCOBERTO</b><br>";
            $contractContent .= "<p style='text-align: justify;'>
        7.1. O CORRENTISTA obriga-se a não realizar qualquer operação bancária que acarrete
        débitos em conta corrente quando o saldo disponível em conta corrente não for suficiente
        para suportar o referido débito. Por saldo disponível entende-se o saldo credor em conta
        corrente, somado a todos os limites de créditos em conta corrente, eventualmente abertos
        pelo BANCO em favor do CORRENTISTA.

        7.2. Verificada qualquer operação bancária cujo montante ultrapassa o saldo disponível em
        conta corrente, o BANCO poderá recusá-la ou, a seu exclusivo critério, e por mera
        liberalidade, acatá-la para preservar a reputação do CORRENTISTA junto ao mercado. Nesse
        caso, por força da realização de operação a descoberto, o BANCO poderá cobrar do
        CORRENTISTA tarifa bancária pelo serviço de levantamento de informações e avaliação de
        viabilidade e de riscos para a admissão da operação a descoberto, juntamente com
        remuneração financeira a título de comissão bancária, para remunerar os custos e despesas
        da captação dos recursos necessários para prover a operação a descoberto, bem como o
        risco de crédito incorrido.

        7.3. O CORRENTISTA autoriza, desde já, o BANCO a resgatar eventual aplicação financeira, a
        fim de liquidar algum saldo a descoberto que venha a ter, sob pena de incorrer em mora,
        passando a incidir os encargos moratórios previstos na cláusula 8 abaixo.
        </p><br>";

            $contractContent .= "<b>8. PRESTAÇÃO DE CONTAS</b><br>";
            $contractContent .= "<p style='text-align: justify;'>
        8.1. O BANCO disponibilizará ao CORRENTISTA mensalmente, extrato das movimentações
        registradas na conta corrente. O BANCO poderá enviar tais extratos ao domicílio do
        CORRENTISTA, ou caso este assim o prefira terá direito a dois extratos mensais, de forma
        gratuita.

        8.2. O CORRENTISTA poderá solicitar ao BANCO comprovantes e documentos que estejam
        relacionados à sua conta, os quais serão disponibilizados em um prazo de até 5 (cinco) dias
        úteis.

        8.3. O CORRENTISTA poderá solicitar especificamente, mediante pagamento da respectiva
        tarifa bancária, formas diferenciadas de prestação de contas das movimentações em conta
        corrente disponibilizadas pelo BANCO, tais como: mais saques mensais além dos gratuitos,
        bem como mais extratos mensais, etc.

        8.4. As tarifas que poderão incidir em sua conta estarão disponíveis através do site do
        BANCO através do link: https://banco.polocal.com.br/login.
        </p><br>";

            $contractContent .= "<b>9. MORA E ENCARGOS MORATÓRIOS</b><br>";
            $contractContent .= "<p style='text-align: justify;'>
        9.1. Caso o CORRENTISTA incorra em mora com relação a qualquer obrigação de
        pagamento de valores decorrente deste Contrato, sobre os valores em mora incidirão: a)
        comissão de permanência, correspondente à taxa média cobrada nas operações praticadas
        pelo mercado financeiro, conforme critérios divulgados pelo Banco Central do Brasil; b)
        juros moratórios à taxa de 1% (um por cento) ao mês, aplicado sobre o saldo devedor
        acrescido da comissão de permanência; e c) multa não indenizatória de 2% (dois por cento)
        sobre o saldo devedor acrescido da comissão de permanência e dos juros moratórios acima
        estipulados.

        9.2. A comissão de permanência e os juros moratórios incidirão diariamente sobre os
        montantes em mora, até a data de seu efetivo pagamento, de forma não capitalizada,
        utilizando-se fator diário calculado com base em um mês de 30 (trinta) dias.

        9.3. O BANCO debitará de qualquer conta corrente de que seja titular o CORRENTISTA os
        montantes correspondentes deste Contrato, acrescidos os respectivos juros e encargos
        moratórios aqui pactuados, ficando, neste ato, de forma irrevogável e irretratável,
        autorizado para tanto.

        9.4. A mora caracterizar-se-á pelo simples descumprimento, na data de seu vencimento, de
        qualquer obrigação decorrente deste Contrato, independentemente de qualquer
        notificação ou interpelação, judicial ou extrajudicial.

        9.5. Caso qualquer das partes incorra em mora com relação às suas obrigações decorrentes
        deste Contrato, a parte credora poderá ressarcir-se dos custos correspondentes à cobrança
        de seus créditos, bem como dos honorários advocatícios.
        </p><br>";

            $contractContent .= "<b>10. MEDIDAS DE SEGURANÇA</b><br>";
            $contractContent .= "<p style='text-align: justify;'>
        10.1. O BANCO adota tecnologias necessárias para a proteção das contas correntes e possui
        ceritifcados digitais de garantia e segurança que estão de acordo com a legislação e
        regulamentações aplicáveis.

        10.2. A senha de movimentação da conta corrente é de extrema importância para
        assegurar a confidencialidade das informações, para isso o CORRENTISTA deverá adotar
        senhas fortes e não permitir que ninguém o veja digitando-a. As senhas deverão ser
        memorizadas e em hipótese alguma devem ser guardadas junto com o cartão do BANCO.

        10.3. O CORRENTISTA não deve divulgar ou transferir a sua senha para terceiros, sendo o
        único responsável pelo uso não autorizado da senha em caso de divulgação ou
        transferência.

        10.4. As transações realizadas em meios eletrônicos poderão ser vulneráveis à ação de
        terceiros, por isso o CORRENTISTA deverá manter o dispositivo móvel em local seguro e
        com mecanismos restritivos de acesso aos seus aplicativos do BANCO. É obrigação do
        CORRENTISTA proteger as senhas de acesso à conta corrente.

        10.5. O CORRENTISTA deverá utilizar os aplicativos instalados sempre com as versões mais
        recentes e ser cuidadoso ao utilizar redes de Wi-Fi públicas.

        10.6. Em caso de perda ou roubo de aparelho móvel que esteja cadastrado para acessar a
        conta corrente, o CORRENTISTA deverá comunicar ao BANCO o ocorrido imediamente para
        a Central de Atendimento do BANCO.

        10.7. O CORRENTISTA não deverá publicar na internet imagens dos cartões de débito ou
        crédito ou qualquer outra informação bancária, a fim de evitar que pessoas mal-
        intencionadas utilizem essas informações para realizarem compras online, que não exigem
        a utilização de senha.

        10.8. O BANCO nunca solicita senhas ou códigos de tokens fora dos ambientes de
        transações bancárias, além disso o BANCO também não solicita dados por e-mail ou SMS.
        Caso o CORRENTISTA receba conteúdos em nome do BANCO que contenham links
        suspeitos ou anexos não solicitados, o CORRENTISTA não deverá acessar o conteúdo para
        evitar que o seu aparelho seja contaminado com vírus.

        10.9. O BANCO poderá enviar mensagens e alertas por e-mail sempre que for solicitado
        pelo CORRENTISTA. Caso o CORRENTISTA não esteja seguro com a informação recebida,
        deverá entrar em contato com a nossa Central de Atendimento para ser auxiliado.

        10.10. O BANCO nunca entra em contato com o CORRENTISTA por telefone para solicitar
        dados pessoais, senhas, tokens, validação ou imagens dos cartões, códigos de identificação
        dos aparelhos móveis ou atualizações. O BANCO também não solicita por telefone o acesso
        a sites de procedimentos de segurança ou atualizações que requeiram a digitação de
        senhas ou códigos se dispositivos de segurança.

        10.11. Para a realização de pagamentos fora da plataforma do Internet Banking do BANCO,
        a solicitação deverá ser feita pelo CORRENTISTA através do e-mail previamente cadastrado
        no momento da abertura da conta. Após a solicitação, o BANCO realiza a conferência da
        assinatura constante no termo de solicitação da operação com o cartão de assinaturas que
        é exigido no momento da abertura da conta.

        10.12. O CORRENTISTA deverá entrar em contato com a Central de Atendimento do BANCO
        imediatamente ao identificar algum procedimento suspeito antes de realizá-lo.
        </p><br>";

            $contractContent .= "<b>11. CONSULTA E COMPARTILHAMENTO DE DADOS</b><br>";
            $contractContent .= "<p style='text-align: justify;'>
        11.1. O CORRENTISTA autoriza, desde já, que o BANCO:
            a) Consulte o Sistema de Informações de Crédito (“SCR”) e a base de dados referentes
            aos recebíveis de arranjo de pagamentos liquidados de forma centralizada do Banco
            Central do Brasil (“BACEN”) com a finalidade de obter auxílio na gestão e tomadas de
            decisões referente ao crédito, visando a mitigação de riscos para o BANCO e/ou
            qualquer das empresas que compõem seu grupo. Os pedidos de correções, de
            exclusões e registro de medidas judiciais e de manifestações de discordância quanto
            às informações constantes do SCR deverão ser dirigidos aos Canais de Atendimento do
            BANCO e/ou empresas que compõem seu grupo, por meio de requerimento escrito e
            fundamentado e, quando for o caso, acompanhado da respectiva decisão judicial.

            b) Troque informações cadastrais, de créditos e débitos, com sistemas positivos e
            negativos de crédito, em especial, com entidades que procedam registros de
            informações e restrição de crédito. O acesso a essas informações, além de proteger as
            operações de crédito realizadas pelo BANCO, também protege o CORRENTISTA de
            eventuais tentativas de fraude.

            c) Na hipótese de a abertura da Conta Corrente ter sido oriunda da indicação de algum
            parceiro do BANCO, compartilharemos a razão social da empresa, o nº da Conta
            Corrente aberta junto ao BANCO, o tipo de produto consumido pelo CORRENTISTA,
            bem como a data da abertura das contas com o parceiro, apenas para fins de
            gerenciamento da parceria e apuração da remuneração correspondente deste parceiro
            que indicou. Caso a Conta Corrente tenha qualquer pendência documental no
            processo de abertura da Conta Corrente junto ao BANCO, o CORRENTISTA autoriza que
            o BANCO comunique ao parceiro sobre qual é a documentação pendente. Essa
            cláusula não se aplica ao CORRENTISTA que não foram indicados por parceiros do
            BANCO.

            d) Enviar conteúdo publicitário a respeito dos produtos e serviços oferecidos pelo
            BANCO, empresas que compõem seu grupo e/ou parceiros. Caso não esteja
            interessado em receber conteúdos publicitários, poderá revogar a sua autorização, a
            qualquer tempo, através dos Canais de Atendimento.

        11.2. O BANCO adota as medidas legais necessárias para proteção de dados. Para mais
        informações, acesse a política de privacidade disponível através do link:
        https://polocalbank.com.br/. Caso tenha alguma dúvida sobre tratamento dos dados
        pessoais, entre em contato com atendimento@polocal.com.br.

        11.3. O BANCO também poderá compartilhar os dados do CORRENTISTA com (i) órgãos
        reguladores para o cumprimento de obrigações legais e regulatórias e (ii) terceiros que
        realizam a prestação de serviços para o BANCO, inclusive no exterior, a fim de viabilizar a
        execução dos serviços listados aqui.

        </p><br>";

            $contractContent .= "<b>12. PRAZO DESTE CONTRATO</b><br>";
            $contractContent .= "<p style='text-align: justify;'>
        12.1. Este Contrato vigorará por prazo indeterminado, podendo ser rescindido a qualquer
        tempo, pelo BANCO ou pelo CORRENTISTA, mediante notificação prévia com antecedência
        de 15 (quinze) dias.

        12.2. Qualquer das partes poderá, ainda, considerar rescindido este Contrato,
        independentemente de notificação prévia, na hipótese de descumprimento de quaisquer
        das obrigações nele previstas ou decorrentes de lei ou normas regulamentares, inclusive
        normas tributárias e aquelas relativas à lavagem de dinheiro.
        12.3. O CORRENTISTA poderá, por sua iniciativa, solicitar a rescisão deste Contrato, com o
        encerramento da conta corrente, preenchendo o formulário para tanto disponibilizado pelo
        BANCO. O BANCO processará o respectivo pedido no prazo até 15 (quinze) dias úteis.

        12.4. Durante o processamento do pedido de encerramento de conta corrente não será
        cobrado o valor correspondente a qualquer Pacote de Serviços contratado.

        12.5. São condições para o encerramento da conta corrente pelo CORRENTISTA: a) no prazo
        máximo de 15 (quinze) dias após a data de solicitação do encerramento da conta corrente,
        realizar o resgate das aplicações financeiras existentes e liquidar eventual saldo devedor
        em conta corrente; b) a entrega de recursos necessários para prover os débitos em conta
        corrente já programados; e c) inexistência de bloqueio judicial com relação à conta
        corrente a ser encerrada.

        12.6. O não cumprimento de quaisquer das condições acima estipuladas resultará no
        cancelamento do pedido de encerramento da conta corrente, podendo o BANCO cobrar os
        valores correspondentes ao Pacote de Serviços eventualmente contratado como se o
        pedido de encerramento da conta corrente nunca tivesse sido realizado.

        12.7. Durante o processamento do encerramento da conta corrente, o BANCO processará
        débitos e créditos na conta corrente que sejam apresentados e cancelará todos os
        pagamentos cadastrados como débito automático. Os cheques apresentados até o
        encerramento da conta serão compensados até os limites disponíveis ou devolvidos pelo
        motivo correspondente. Após o encerramento da conta, os cheques serão devolvidos por
        motivo de “conta encerrada”, podendo assim, ser o CORRENTISTA inscrito no Cadastro de
        Emitentes de Cheque Sem Fundo do Banco Central do Brasil.
        </p><br>";

            $contractContent .= "<b>13. CONSEQUÊNCIAS DO ENCERRAMENTO DA CONTA CORRENTE</b><br>";
            $contractContent .= "<p style='text-align: justify;'>
        13.1. Rescindido este Contrato e encerrada a conta corrente o CORRENTISTA não poderá
        realizar as operações bancárias e deverá devolver ao BANCO as folhas de cheques em seu
        poder, ou então apresentar declaração de que as inutilizou, responsabilizando-se por todas
        as consequências advindas da devolução de quaisquer cheques apresentados para
        compensação após a efetiva rescisão do Contrato.

        13.2. Se, na data de seu encerramento, houver saldo credor na conta corrente, esse ficará à
        disposição do CORRENTISTA, na conta corrente na qual estava escriturada, sem curso de
        juros ou atualização monetária.

        13.3. O BANCO expedirá aviso ao CORRENTISTA, que poderá ser por meio eletrônico,
        informando a data do efetivo encerramento da conta de depósito à vista.
        </p><br>";

            $contractContent .= "<b>14. EMISSÃO DE CHEQUES</b><br>";
            $contractContent .= "<p style='text-align: justify;'>
        14.1. O CORRENTISTA poderá sacar contra o BANCO ordens de pagamento por meio da
        emissão de cheques, em obediência à legislação e regulamentação vigente.

        14.2. O BANCO fornecerá ao CORRENTISTA as respectivas folhas de cheque, sendo certo
        que o fornecimento de dez folhas mensais será isento de qualquer tarifa, nos termos da
        regulamentação bancária vigente aplicável. O fornecimento de folhas adicionais ficará
        sujeito ao pagamento da respectiva tarifa, conforme informada pelo BANCO em suas
        Tabelas em local e formato visível. O número de folhas de cheques fornecidos em cada mês
        será apurado de acordo com o número de cheques emitidos pelo CORRENTISTA e
        apresentados para pagamento.

        14.3. O CORRENTISTA será responsável pela guarda e custódia das folhas de cheques que
        lhe forem fornecidas pelo BANCO, devendo comunicá-lo, imediatamente, no caso de perda,
        furto ou roubo de quaisquer de tais folhas. Será de inteira responsabilidade do
        CORRENTISTA o pagamento pelo BANCO, de qualquer cheque perdido, furtado ou roubado
        que não lhe tenha sido comunicado.

        14.4. O fornecimento de talonário de cheques dependerá da inexistência de restrições
        cadastrais junto ao Cadastro de Emitentes de Cheques sem Fundos ou junto aos Cadastros
        internos do BANCO, em nome do CORRENTISTA ou de seus responsáveis.

        14.5. Os talonários de cheques poderão ser solicitados pelo CORRENTISTA por qualquer
        meio colocado à sua disposição pelo BANCO e serão disponibilizados de acordo com as
        normas em vigor.

        14.6. O BANCO pagará cheques emitidos pelo CORRENTISTA ao respectivo beneficiário,
        sendo certo que, no caso de cheques endossados, o BANCO conferirá apenas a
        regularidade formal do endosso, sem se responsabilizar pela autenticidade das assinaturas.

        14.7. Observadas as normas do Banco Central do Brasil, os cheques pagos ou liquidados
        poderão a critério do BANCO, ser destruídos, desde que microfilmados, reservando-se ao
        CORRENTISTA o direito à requisição de cópias em qualquer eventualidade.

        14.8. Na hipótese de cheque apresentado para pagamento após o encerramento da conta
        corrente, que não tenha sido sustado, revogado ou cancelado, e que não tenha corrido seu
        prazo prescricional, este será devolvido pelo motivo de “conta encerrada”.


        </p><br>";

            $contractContent .= "<b>15. REGISTRO EM CONTA CORRENTE</b><br>";
            $contractContent .= "<p style='text-align: justify;'>
        15.1. O CORRENTISTA concorda expressamente que o BANCO debite em sua conta corrente:
        a) o valor correspondente a todos os cheques emitidos e efetivamente pagos; b) o valor
        correspondente a todas as transferências de recurso cursadas; c) o valor correspondente a
        todos os pagamentos a terceiros realizados em conta corrente; e, d) o valor
        correspondente a todas as tarifas devidas nos termos da legislação vigente.

        15.2. O BANCO creditará em conta corrente os valores correspondentes às ordens de
        pagamento emitidas em benefício do CORRENTISTA, imediatamente após tê-los recebido.

        15.3. O CORRENTISTA obriga-se a não emitir ordens de pagamento contra o BANCO sem
        dispor, em sua conta corrente, de saldo credor suficiente. Para fins do aqui disposto,
        integrará o saldo credor da conta corrente o limite de quaisquer aberturas de crédito em
        conta corrente contratadas pelo CORRENTISTA junto ao BANCO.
        </p><br>";

            $contractContent .= "<b>16. DA AUTORIZAÇÃO PARA INFORMAÇÕES</b><br>";
            $contractContent .= "<p style='text-align: justify;'>
        16.1. O CORRENTISTA autoriza o BANCO a trocar informações creditícias, cadastrais e
        financeiras a seu respeito e a utilizar seu endereço, inclusive eletrônico, para o envio de
        malas diretas, venda de produtos e serviços, outras correspondências promocionais e,
        ainda, está ciente e dá prévia autorização ao BANCO e/ou as empresas do mesmo grupo
        econômico a ele ligadas ou por ele controladas, bem como seus sucessores, a consultar os
        débitos e responsabilidades decorrentes de operações com características de crédito que
        constem ou venham a constar em seu nome no Sistema de Informações de Crédito (SCR)
        gerido pelo Banco Central (BACEN), ou nos sistemas que venham a complementá-lo e/ou a
        substituí-lo. Está ciente também que: a) os débitos e responsabilidades decorrentes de
        operações com características de crédito realizadaspor ele junto ao BANCO poderão ser
        registrados no SCR; b) o SCR tem por finalidades: (i) fornecer informações ao BACEN para
        fins de supervisão do risco de crédito a que estão expostas as instituições financeiras e (ii)
        propiciar o intercâmbio dessas informações entre essas instituições com o objetivo de
        subsidiar decisões de crédito e de negócios; c) o CORRENTISTA poderá ter acesso aos dados
        constantes em seu nome no SCR por meio da Central de Atendimento ao Público do Banco
        Central BACEN; d) os pedidos de correções, de exclusões e registros de medidas judiciais e
        de manifestações de discordância quanto as informações constantes do SCR deverão ser
        dirigidas ao BANCO por meio de requerimento escrito e fundamentado e, quando for caso,
        acompanhado da respectiva decisão judicial.
        </p><br>";

            $contractContent .= "<b>17. DISPOSIÇÕES GERAIS</b><br>";
            $contractContent .= "<p style='text-align: justify;'>
        17.1. Garantias Adicionais. Cada uma das Partes obriga-se a assinar todos os documentos e
        a praticar todos os atos que venham a ser razoavelmente exigidos ou convenientes ao
        cumprimento das disposições deste Contrato e à consecução das operações aqui previstas.

        17.2. Modificações e Alterações. Este Contrato somente poderá ser alterado por
        instrumento escrito assinado por todas as Partes.

        17.3. Acordo Integral. O presente Contrato, seus anexos, os instrumentos referidos neste
        Contrato e os acordos, documentos e instrumentos a serem assinados e entregues nos
        termos deles constituem o acordo final, cabal e exclusivo entre as Partes e substituem
        todos os acordos, entendimentos e declarações anteriores, orais ou escritos a esse respeito
        e, ainda, não poderão ser contrariados por prova desse acordo, entendimento ou
        declaração anterior ou contemporâneo, oral ou escrito.

        17.4. Sucessão. O presente Contrato e os direitos, avenças, condições e obrigações das
        Partes, vincularão as Partes e seus respectivos sucessores, cessionários e representantes
        legais.

        17.5. Divisibilidade. Na hipótese de qualquer disposição ou parte de qualquer disposição
        deste Contrato ser considerada nula, anulada ou inexequível por qualquer motivo, essa
        disposição será suprimida e não terá nenhuma força e efeito, permanecendo as demais
        disposições deste Contrato em pleno vigor e efeito, e, na medida do necessário, serão
        modificadas para preservar sua validade.

        17.6. Cumprimento Legal. Cada Parte é inteiramente responsável pelo cumprimento e
        observância de todas as normas, regulamentos, estatutos, códigos, portarias e outros
        requisitos aplicáveis ao tipo de atividade desenvolvida por cada uma delas.

        17.7. Foro. Fica eleito o foro da Comarca de BRASILIA (DF), para dirimir possíveis e futuras
        dúvidas, que possam surgir, na interpretação das cláusulas deste Contrato.
        </p><br>";

            $contractContent .= "<p style='text-align: justify;'>
        Declaro para os devidos fins e sob as penas da Lei, que são verídicos e corretos os dados cadastrais
        constantes na Ficha Cadastral e que li e entendi claramente e aceito todas as cláusulas e condições
        do presente contrato.
        Autorizo ainda ao Banco POLOCAL BANK a verificar qualquer das informações por mim prestadas,
        a consultar o Sistema de Informações de Crédito, organizado pelo Banco Central do Brasil, sobre
        eventuais informações a meu (nosso) respeito.



        Responsabilizo-me pela exatidão das informações prestadas a vista dos originais do contrato social,
        do CNPJ e outros comprobatórios dos demais elementos de informações apresentadas, sob pena
        de aplicação do disposto no artigo 64 da lei n° 8.383 de 30-12-1991.
        Assumo o compromisso de comunicar expressa e imediatamente ao Banco 2 qualquer alteração
        nas declarações e informações
        aqui prestadas, bem como, a atender os procedimentos necessários para a atualização de meu
        cadastro nesta instituição.
        E assim, por estarem justos e contratados, firmam o presente instrumento, em E por estarem as
        partes assinam o presente instrumento no Li concordo com os termos.
        Nome do Gestor de Negócios responsável pela conta: ___________________________________
        Assinatura do Gestor de Negócios_____________________________
                </p><br>";

            $contractContent .= "<b>Local:</b> {$user->city}<br>";
            $contractContent .= "<b>Data de Criação:</b> " . date("d/m/Y") . "<br>";

            $contractContent .= "<b>Assinatura do Cliente:</b> {$user->name}<br>";



            $contract = new OpenContract();
            $contract->title = 'Contrato de Abertura de Conta Corrente';
            $contract->content = $contractContent;
            $contract->user_id = $user->id;
            $contract->save();

            // Verifica se o contrato foi salvo com sucesso
            Log::info('Contrato salvo com sucesso. ID: ' . $contract->id);

            // Associa o contrato ao usuário
            $user->open_contract_id = $contract->id;
            $user->save();


            //Service
            $this->businessAcountService->businessRegister($user->toArray());

            // Verifica se o usuário foi associado ao contrato
            Log::info('Usuário associado ao contrato. Contrato ID: ' . $contract->id . ', Usuário ID: ' . $user->id);

            DB::commit();
            // Verifica se a transação foi concluída com sucesso
            Log::info('Transação concluída com sucesso.');

            Auth::login($user);
            return redirect('/dashboard');

        } catch (\Exception $e) {
            DB::rollback();
            // Registra erro
            Log::error('Erro ao criar contrato: ' . $e->getMessage());

            // Trate o erro conforme necessário
            return back()->withInput()->withErrors(['message' => 'Erro ao criar contrato. Por favor, tente novamente.']);
        }
    }

}

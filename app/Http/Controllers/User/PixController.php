<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pix;
use Auth;
use App\Services\StarbankService;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use App\Services\MyBank\Payment\Pik\PaymentPixService;
use App\Services\MyBank\PixService;

class PixController extends Controller
{
    protected $pixService;

    public function __construct(PaymentPixService $pixService)
    {
        $this->pixService = $pixService;
    }

    public function index()
    {
        return view('users.pix.index');
    }

    public function qrcode()
    {
        return view('users.pix.qrcode');
    }

    public function extract()
    {
        $transactions = Transaction::where('user_id', Auth::id())->where('method', Transaction::METHOD_PIX)->orderBy('created_at', 'DESC')->get();

        return view('users.pix.extract', compact('transactions'));
    }

   public function store(Request $request)
{
    // Validar o request
    $request->validate([
        'amount' => ['required', 'string', function ($attribute, $value, $fail) {
            if (!preg_match('/^\d{1,3}(?:\.\d{3})*(?:,\d{2})?$/', $value)) {
                $fail('O campo ' . $attribute . ' tem um formato inválido.');
            }
        }],
    ]);

    $amount = convertAmountToInt($request->amount);
    $user = Auth::user();

    // Montar o payload
    $payload =  [
    "CustomId" => $user->id,
    "amount" => $amount,
    "dueDate" => "30/07/2025",
    "customer" => [
        "name" => $user->name,
        "email" => $user->email,
        "cpfcnpj" => $user->documentNumber,
        "addres" =>  $user->address,
        "neighborhood" => $user->neighborhood,
        "city" => $user->city,
        "state" => $user->state,
        "country" => "BRASIL",
        "zipcode" => $user->zipCode,
    ],

    "confirmationUrl" => "https://webhook.site/495472fc-207d-45ae-8c5d-731ec32779d4",
];


    DB::beginTransaction();

    try {
        // Utilize o serviço já injetado
        $paymentService = $this->pixService;

        // Gere o QR code junto ao serviço
        $qrcode = $paymentService->PaymentGeneratePix($payload);

        // Salve a transação
        $transaction = new Transaction([
            'user_id'   => $user->id,
            'code'      => $qrcode['id'], // ou $qrcode->id
            'amount'   => $qrcode['amount'] / 100,
            'status'   => Transaction::STATUS_CRIADO,
            'externalId' => $qrcode['txId'], // ou $qrcode->id
            'description' => 'Gerou QR Code de pagamento Pix',
            'type'     => Transaction::TYPE_DYNAMIC_BRCODE,
            'operacao' => Transaction::OPERACAO_CREDIT,
            'method'   => Transaction::METHOD_PIX,
            'fee'      => 0.04,
            'invoiceCode' => $qrcode['invoiceCode'],
            'qrCodeString' => $qrcode['qrCodeString'],


        ]);

        $transaction->save();

        DB::commit();

        return redirect()
            ->route('pix.qrcode.view', $transaction->id)
            ->with('success', 'QR Code criado com sucesso.');
    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('Erro ao criar QR Code Pix: ' . $e->getMessage());

        return redirect()
            ->back()
            ->with('error', 'Erro ao criar QR Code Pix: ' . $e->getMessage());
    }
}

    public function view($id)
    {
        $brcode = Transaction::where('user_id', Auth::id())->find($id);
        if (!$brcode) {
            return redirect()
                ->back()
                ->with('error', 'QR Code não encontrado');
        }

        return view('users.pix.view_qrcode', compact('brcode'));
    }

    /**
     * Webhook que recebe a confirmação do pagamento Pix
     */
    public function webhook(Request $request)
    {
        $payload = $request->all();

        $transaction = Transaction::where('externalId', $payload['transactionId'] ?? '')->first();

        if ($transaction) {
            $transaction->status = Transaction::STATUS_PAGO;
            $transaction->save();

            return response('Pagamento atualizado!', 200);
        } else {
            return response('Transação não encontrada!', 404);
        }
    }
}

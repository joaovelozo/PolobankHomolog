<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Cache;

class PhoneValidatorController extends Controller
{
    // Exibe o formulário para o número de telefone
    public function showPhoneForm()
    {
        return view('auth.phone');
    }

    // Envia o código de verificação para o WhatsApp
    public function sendVerificationCode(Request $request)
    {
        // Valida o número de WhatsApp, permitindo o formato nacional
        $request->validate([
            'whatsapp' => 'required|string|regex:/^\(?\d{2}\)?\s?\d{4,5}-\d{4}$/', // Permite números no formato (XX) XXXXX-XXXX
        ]);

        // Formata o número para o formato internacional (+55XXXXXXXXX)
        $whatsapp = $this->formatPhoneNumber($request->input('whatsapp'));

        $code = rand(10000000, 99999999); // Gera um código aleatório de 8 dígitos

        // Armazena o código no cache por 10 minutos
        Cache::put('auth_code_' . $whatsapp, $code, now()->addMinutes(10));

        // Envia o código via WhatsApp
        try {
            $this->sendWhatsAppMessage($whatsapp, $code);
            return response()->json([
                'status' => 'success',
                'whatsapp' => $whatsapp,
                'message' => 'Código enviado com sucesso!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao enviar o código. Tente novamente.'
            ], 500);
        }
    }

    // Método para formatar o número de telefone no formato internacional (+55XXXXXXXXX)
    private function formatPhoneNumber($phone)
    {
        // Remove todos os caracteres não numéricos
        $phone = preg_replace('/\D/', '', $phone);

        // Verifica se o número começa com o DDD (2 dígitos) e o número de telefone
        if (strlen($phone) == 11) {
            // Adiciona o prefixo do Brasil (+55)
            return '+55' . $phone;
        }

        throw new \Exception('Número de telefone inválido');
    }

    // Método para enviar a mensagem via WhatsApp usando Twilio
    private function sendWhatsAppMessage($whatsapp, $code)
    {
        try {
            // Configurações do Twilio
            $sid = env('TWILIO_SID');
            $token = env('TWILIO_AUTH_TOKEN');
            $twilioNumber = env('TWILIO_WHATSAPP_NUMBER');

            // Mensagem a ser enviada
            $message = "Seu código de verificação é: $code";

            // Cria uma instância do cliente Twilio
            $client = new Client($sid, $token);

            // Envia a mensagem para o WhatsApp do usuário
            $client->messages->create(
                "whatsapp:$whatsapp",
                [
                    'from' => $twilioNumber,
                    'body' => $message,
                ]
            );
            \Log::info('Resposta do Twilio: ' . json_encode($message));
            \Log::info("Tentando enviar mensagem para o WhatsApp: {$whatsapp} com o código: {$code}");
        } catch (\Exception $e) {
            // Registra o erro para ver detalhes
            \Log::error('Erro ao enviar mensagem via WhatsApp: ' . $e->getMessage());
            throw $e; // Opcional: re-lança a exceção para tratamento adicional
        }
    }

    // Exibe o formulário para o código de verificação
    public function showCodeForm()
    {
        return view('auth.code');
    }

    // Valida o código de verificação enviado
    public function validateCode(Request $request)
    {
        $request->validate([
            'whatsapp' => 'required|string|regex:/^\+?[1-9]\d{10,14}$/', // Formato internacional
            'code' => 'required|numeric|digits:8',  // Valida o código de 8 dígitos
        ]);

        $whatsapp = $request->input('whatsapp');
        $code = $request->input('code');

        $cachedCode = Cache::get('auth_code_' . $whatsapp);

        if ($cachedCode && $cachedCode == $code) {
            Cache::forget('auth_code_' . $whatsapp);
            return response()->json(['message' => 'Código validado com sucesso!']);
        }

        return response()->json(['message' => 'Código inválido ou expirado!'], 422);
    }

}

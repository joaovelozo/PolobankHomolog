<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Transaction extends Model
{
    use HasFactory;
       protected $guarded = [];


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($transaction) {
            $transaction->token = $transaction->generateToken();
        });
    }

    public function generateToken()
    {
        $date = Carbon::now()->format('YmdHis');
        $randomNumbers = Str::random(10);

        return $date . $randomNumbers;
    }

    // Tipos de método
    const METHOD_PIX = 'PIX';
    const METHOD_BOLETO = 'BOLETO';
    const METHOD_TRANSFER = 'TRANSFER';

    // Tipos de operação
    const OPERACAO_CREDIT = 'CREDIT';
    const OPERACAO_DEBIT = 'DEBIT';
    const OPERACAO_INVESTMENT = 'INVESTMENT';

    // Status da transação
    const STATUS_CRIADO = 'CREATED'; //0
    const STATUS_PROCESSAMENTO = 'PROCESSING'; //1
    const STATUS_SUCESSO = 'SUCCESS'; //2
    const STATUS_FALHOU = 'FAILED'; //3
    const STATUS_CANCELADO = 'CANCELED'; //4
    const STATUS_EXPIRADO = 'EXPIRED'; //5
    const STATUS_REEMBOLSADO = 'REFUNDED'; //6


    // Tipos de transação
    const TYPE_INVOICE = 'INVOICE';
    const TYPE_DYNAMIC_BRCODE = 'DYNAMIC_BRCODE';
    const TYPE_DEPOSIT = 'DEPOSIT';
    const TYPE_BOLETO = 'BOLETO';
    const TYPE_TRANSFER = 'TRANSFER';
    const TYPE_BRCODE_PAYMENT = 'BRCODE_PAYMENT';
    const TYPE_BOLETO_PAYMENT = 'BOLETO_PAYMENT';
    const TYPE_UTILITY_PAYMENT = 'UTILITY_PAYMENT';

    // Método para obter a descrição do status
    public function getStatusDescription()
    {
        $statuses = [
            self::STATUS_CRIADO => 'Criado',
            self::STATUS_PROCESSAMENTO => 'Processando',
            self::STATUS_SUCESSO => 'Pago',
            self::STATUS_FALHOU => 'Rejeitado',
            self::STATUS_CANCELADO => 'Cancelado',
            self::STATUS_EXPIRADO => 'Expirado',
            self::STATUS_REEMBOLSADO => 'Reembolsado',
        ];
        return $statuses[$this->status] ?? 'Desconhecido';
    }

    // Método para obter a descrição da operação
    public function getOperacaoDescription()
    {
        $operacoes = [
            self::OPERACAO_CREDIT => 'Entrada',
            self::OPERACAO_DEBIT => 'Saída',
            self::OPERACAO_INVESTMENT => 'Investimento',
        ];
        return $operacoes[$this->operacao] ?? 'Desconhecido';
    }

    // Método para obter a descrição do método
    public function getMetodoDescription()
    {
        $metodos = [
            self::METHOD_PIX => 'Pix',
            self::METHOD_BOLETO => 'Boleto',
            self::METHOD_TRANSFER => 'Transferência',
        ];
        return $metodos[$this->method] ?? 'Desconhecido';
    }

    // Método para obter a descrição do tipo de transação
    public function getTypeDescription()
    {
        $types = [
            self::TYPE_INVOICE => 'Fatura',
            self::TYPE_DYNAMIC_BRCODE => 'QR Code Dinâmico',
            self::TYPE_DEPOSIT => 'Depósito',
            self::TYPE_BOLETO => 'Boleto',
            self::TYPE_TRANSFER => 'Transferência',
            self::TYPE_BRCODE_PAYMENT => 'Pagamento QR Code',
            self::TYPE_BOLETO_PAYMENT => 'Pagamento de Boleto',
            self::TYPE_UTILITY_PAYMENT => 'Pagamento de Serviços',
        ];
        return $types[$this->type] ?? 'Desconhecido';
    }

    // Definindo a relação com o usuário associado à transação (quem enviou ou recebeu a transação)
    public function user()
    {
        // Assumindo que o usuário associado à transação é sempre o remetente (sender)
        return $this->belongsTo(User::class, 'user_id');
    }
}

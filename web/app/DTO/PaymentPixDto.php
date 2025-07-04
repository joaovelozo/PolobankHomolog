<?php
namespace App\DTO;

class PaymentPixDto
{
    private string $id;
    private string $txId;
    private int $amount;
    private string $qrCodeString;

    public function __construct(array $payload)
    {
        $this->id = $payload['resposta']['id'];
        $this->txId = $payload['resposta']['txId'];
        $this->amount = $payload['resposta']['amount'];
        $this->qrCodeString = $payload['resposta']['qrCodeString'];
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getTxId()
    {
        return $this->txId;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getQrCodeString()
    {
        return $this->qrCodeString;
    }
}

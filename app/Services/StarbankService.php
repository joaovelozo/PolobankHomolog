<?php

namespace App\Services;

use StarkBank\Project;
use StarkBank\Settings;
use StarkBank\Organization;
use StarkBank\Transfer;
use StarkBank\Balance;
use StarkBank\Key;
use StarkBank\BrcodePayment;
use StarkBank\BoletoPayment;
use StarkBank\DynamicBrcode;
use StarkBank\Transaction;
use StarkBank\Invoice;
use StarkBank\PaymentPreview;
use StarkBank\Webhook;
use StarkBank\Event;
use StarkBank\Boleto;
use StarkBank\Error\InputErrors;
use StarkBank\Event\Attempt;
use StarkBank\DictKey;

class StarbankService
{
    public function __construct()
    {
        $this->setup();
    }

    private function setup()
    {
        $privateKeyContent =  env('STARKBANK_PRIVATE_KEY');
        $environment =  env('STARKBANK_ENV');
        $id =  env('STARKBANK_PROJECT_ID');

        $user = new Project([
            "environment" => $environment,
            "id" => $id,
            "privateKey" => $privateKeyContent
        ]);
        Settings::setUser($user);
        Settings::setLanguage("pt-BR");
    }

    /**
     * Get the current balance
     *
     * @return \StarkBank\Balance
     * @throws \Exception
     */
    public function balance()
    {
        try {
            $balance = Balance::get();
            return $balance;
        } catch (\Exception $e) {
            throw new \Exception("Error getting balance: " . $e->getMessage());
        }
    }

    public function tranferencia($amount, $bankCode, $branchCode, $accountNumber, $taxId, $name, $user)
    {
        try {
            $transfers = Transfer::create([
                new Transfer([
                    "amount" => $amount,
                    "bankCode" => $bankCode,
                    "branchCode" => $branchCode,
                    "accountNumber" => $accountNumber,
                    "taxId" => $taxId,
                    "name" => $name,
                    "tags" => ["user: #$user->id"]
                ])
            ]);
            return $transfers;
        } catch (\Exception $e) {
            throw new \Exception("Error creating transfer: " . $e->getMessage());
        }
    }

    public function criaBoletoRecebimento($amount, $name, $taxId, $rua, $complemento, $bairro, $cidade, $estado, $cep, $user)
    {
        try {
            $transfers = Boleto::create([
                new Boleto([
                    "amount" => $amount,
                    "name" => $name,
                    "taxId" => $taxId,
                    "streetLine1" => $rua,
                    "streetLine2" => $complemento,
                    "district" => $bairro,
                    "city" => $cidade,
                    "stateCode" => $estado,
                    "zipCode" => $cep,
                    "due" => date('Y-m-d', strtotime('+30 days')),
                    "tags" => ["user: #$user->id"]
                ])
            ]);
            return $transfers;
        } catch (\Exception $e) {
            throw new \Exception("Error creating transfer: " . $e->getMessage());
        }
    }


    public function criaQrCodeRecebimento($amount, $user)
    {
        try {
            $payments = DynamicBrcode::create([
                new DynamicBrcode([
                    "amount" => $amount,
                    "expiration" => 7776000,
                    "tags" => ["user: #$user->id"],
                ])
            ]);
            return $payments;
        } catch (\Exception $e) {
            throw new \Exception("Error criaQrCodeRecebimento: " . $e->getMessage());
        }
    }


    public function criaQrCodePagamento($qrcode,$amount, $taxId, $description, $user)
    {
        try {
            $payments = BrcodePayment::create([
                new BrcodePayment([
                    "brcode" => $qrcode,
                    "amount" => $amount,
                    "taxId" => $taxId,
                    "description" => $description,
                    "tags" => ["user: #$user->id"],
                ])
            ]);
            return $payments;
        } catch (\StarkBank\Error\InputErrors $e) {
            $errors = [];
            foreach ($e->errors as $error) {
                $errors[] = "Código: " . $error->errorCode . " - Mensagem: " . $error->errorMessage;
            }
            throw new \Exception("Erro na criação do pagamento Brcode: " . implode(", ", $errors));
        } catch (\Exception $e) {
            throw new \Exception("Erro ao criar pagamento Brcode: " . $e->getMessage());
        }
    }

    public function criaBoletoPagamento($line, $taxId, $description, $user)
    {
        try {
            $payments = BoletoPayment::create([
                new BoletoPayment([
                    "line" => $line,
                    "taxId" => $taxId,
                    "description" => $description,
                    "tags" => ["user: #$user->id"],
                ])
            ]);
            return $payments;
        } catch (\Exception $e) {
            throw new \Exception("Error criaBoletoPagamento: " . $e->getMessage());
        }
    }

    public function criaFatura($amount, $taxId, $name, $user)
    {
        try {
            $payment = Invoice::create([
                new Invoice([
                    "amount" => $amount,
                    "taxId" => $taxId,
                    "name" => $name,
                    "tags" => ["user: #$user->id"],
                ])
            ]);
            return $payment;
        } catch (\Exception $e) {
            throw new \Exception("Error criaFatura: " . $e->getMessage());
        }
    }


    public function listarQrCodesPagamento($user)
    {
        try {
            $payments = BrcodePayment::query([
                "tags" => ["user: #$user->id"]
            ]);
            return $payments;
        } catch (\Exception $e) {
            throw new \Exception("Error listarQrCodesPagamento: " . $e->getMessage());
        }
    }

    public function listarFaturas($user)
    {
        try {
            $payments = Invoice::query([
                "tags" => ["user: #$user->id"]
            ]);
            return $payments;
        } catch (\Exception $e) {
            throw new \Exception("Error listarFaturas: " . $e->getMessage());
        }
    }

    public function listarBoletosPagamento($user)
    {
        try {
            $payments = iterator_to_array(BoletoPayment::query([
                "tags" => ["user: #$user->id"]
            ]));
            return $payments;
        } catch (\Exception $e) {
            throw new \Exception("Error  listarBoletosPagamento: " . $e->getMessage());
        }
    }

    public function buscaBoletoPagamento($id)
    {
        try {
            $pagamento = BoletoPayment::get($id);
            return $pagamento;
        } catch (\Exception $e) {
            throw new \Exception("Error buscaBoletoPagamento: " . $e->getMessage());
        }
    }

    public function buscaBoletoRecebimento($id)
    {
        try {
            $pagamento = Boleto::get($id);
            return $pagamento;
        } catch (\Exception $e) {
            throw new \Exception("Error buscaBoletoRecebimento: " . $e->getMessage());
        }
    }

    public function buscaBoletoRecebimentoPDF($id)
    {
        try {
            $pagamento = Boleto::pdf($id);
            return $pagamento;
        } catch (\Exception $e) {
            throw new \Exception("Error buscaBoletoRecebimentoPDF: " . $e->getMessage());
        }
    }

    public function buscaFatura($id)
    {
        try {
            $pagamento = Invoice::get($id);
            return $pagamento;
        } catch (\Exception $e) {
            throw new \Exception("Error buscaFatura: " . $e->getMessage());
        }
    }

    public function buscaQrCodePagamento($id)
    {
        try {
            $pagamento = BrcodePayment::get($id);
            return $pagamento;
        } catch (\Exception $e) {
            throw new \Exception("Error buscaQrCodePagamento: " . $e->getMessage());
        }
    }

    public function listarQrCodesRecebimento($user)
    {
        try {
            $payments = iterator_to_array(DynamicBrcode::query([
                "tags" => ["user: #$user->id"]
            ]));
            return $payments;
        } catch (\Exception $e) {
            throw new \Exception("Error listarQrCodesRecebimento: " . $e->getMessage());
        }
    }

    public function listarTranferencias($user)
    {
        try {
            $payments = iterator_to_array(Transfer::query([
                "tags" => ["user: #$user->id"]
            ]));
            return $payments;
        } catch (\Exception $e) {
            throw new \Exception("Error listarTranferencias: " . $e->getMessage());
        }
    }

    public function listarTransacoes()
    {
        try {
            $payments = iterator_to_array(Transaction::query());
            return $payments;
        } catch (\Exception $e) {
            throw new \Exception("Error listarTransacoes: " . $e->getMessage());
        }
    }

    public function buscaQrCodeRecebimento($id)
    {
        try {
            $pagamento = DynamicBrcode::get($id);
            return $pagamento;
        } catch (\Exception $e) {
            throw new \Exception("Error buscaQrCodeRecebimento: " . $e->getMessage());
        }
    }

    public function buscaTransferencia($id)
    {
        try {
            $pagamento = Transfer::get($id);
            return $pagamento;
        } catch (\Exception $e) {
            throw new \Exception("Error buscaTransferencia: " . $e->getMessage());
        }
    }


    public function pagamentoPreview($id)
    {
        try {
            $payment = PaymentPreview::create([
                new PaymentPreview([
                    "id" => $id,
                ])
            ]);
            return $payment;
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getDictKey($pix)
    {
        try {
            $pix = DictKey::get($pix);
            return $pix;
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    public function events()
    {
        try {
            $events =  iterator_to_array(Event::query());
            return $events;
        } catch (\Exception $e) {
            throw new \Exception("Error: " . $e->getMessage());
        }
    }
}

<?php
namespace App\Services\MyBank;

class cprService
{
    protected $cprToken;
    public function __construct()
    {
        $this->cprToken = env('MB_CRP_TOKEN');
    }

    public function generateSignature($token)
    {
        return hash_hmac('sha256', $token, $this->cprToken);
    }

}

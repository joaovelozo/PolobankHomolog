<?php

namespace App\Services\MyBank\Auth;

use App\Services\MyBank\MBService;


class PersonalAccountService
{
    protected $mbService;

    public function __construct(MBService $mbService)
    {
        $this->mbService = $mbService;
    }

    public function personalRegister(array $userData)
    {
        // Aqui você pode adaptar e montar o payload como desejar
       $payload = [
    "sendNotificationsToOwner" => false,
    "typePerson" => 1,
    "legalPerson" => null,
    "naturalPerson" => [
        "documentNumber" => $userData['documentNumber'],
        "identityDocument" => $userData['identityDocument'],
        "issueDate" => $userData['issueDate'],
        "issuingAgency" => $userData['issuingAgency'],
        "issuingState" => $userData['issuingState'],
        "name" => $userData['name'],
        "gender" => $userData['gender'],
        "birthDate" => $userData['birthdate'],
        "idMaritalStatus" => $userData['idMaritalStatus'],
        "political" => $userData['political'],
        "rent" => $userData['rent'],
        "nameMother" => $userData['nameMother'],
    ],
    "address" => [
        "zipCode" => $userData['zipCode'],
        "addressNumber" => $userData['addressNumber'],
        "address" => $userData['address'],
        "state" => $userData['state'],
        "city" => $userData['city'],
        "neighborhood" => $userData['neighborhood'],
        "complement" => $userData['complement'] ?? '',
    ],
    "contact" => [
        "email" => $userData['email'],
        "phoneNumber" => $userData['phoneNumber'],
        "cellPhone" => $userData['cellPhone'],
        "commercialPhone" => $userData['commercialPhone'] ?? null,
    ],
    "userAdmin" => [
        "name" => $userData['name'],
        "email" => $userData['email'],
        "username" => $userData['username'],
        "password"=> 'HikSzNULrR',
    ],
    "imageself" => [
        "tipo" => "image",
        "base64" => $userData['imageself'],
    ],
    "imagedoc" => [
        "tipo" => "image",
        "base64" => $userData['imagedoc'],
    ],
    "imagedoc_verso" => [
        "tipo" => "image",
        "base64" => $userData['imagedoc_verso'],
    ],
   "imagecomprovante" => [
    "tipo" => "image",
    "base64" => $userData['imagecomprovante'],
],
     "confirmationUrl" => env('MB_CONFIRMATION_URL'),
        "defaultWebhook" => env('MB_DEFAULT_WEBHOOK'),
        "createAPICredentials" => true,
    ];

    $return = $this->mbService->createCustomer($payload);
    return $return;
    }


}

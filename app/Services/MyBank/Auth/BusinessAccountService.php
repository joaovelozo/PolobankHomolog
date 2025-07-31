<?php

namespace App\Services\MyBank\Auth;

use App\Services\Mybank\businessService;
use App\Services\MyBank\MBService;

class BusinessAccountService
{
    protected $mbService;

    public function __construct(MBService $mbService)
    {
        $this->mbService = $mbService;
    }

    public function businessRegister(array $userData)
    { // Valida os campos obrigatórios
        $required = [
            'businessZipCode',
            'businessAddressNumber',
            'businessAddress',
            'businessState',
            'businessCity',
            'businessNeighborhood',
            'zipCode',
            'addressNumber',
            'address',
            'state',
            'city',
            'neighborhood',
        ];

        foreach ($required as $field) {
            if (empty($userData[$field])) {
                throw new \InvalidArgumentException("O campo {$field} é obrigatório.");
            }
        }

        // Agora que sabemos que estão presentes, podemos usar
        $businessZipCode = $userData['businessZipCode'];
        $businessAddressNumber = $userData['businessAddressNumber'];
        $businessAddress = $userData['businessAddress'];
        $businessState = $userData['businessState'];
        $businessCity = $userData['businessCity'];
        $businessNeighborhood = $userData['businessNeighborhood'];
        $businessComplement = $userData['businessComplement'] ?? '';

        $partnerZipCode = $userData['zipCode'];
        $partnerAddressNumber = $userData['addressNumber'];
        $partnerAddress = $userData['address'];
        $partnerState = $userData['state'];
        $partnerCity = $userData['city'];
        $partnerNeighborhood = $userData['neighborhood'];
        $partnerComplement = $userData['complement'] ?? '';

        $payload = [
            "sendNotificationsToOwner" => false,
            "typePerson" => "2",
            "political_representative" => 1,
            "legalPerson" => [
                "documentNumber" => $userData['documentBusiness'],
                "name" => $userData['companyName'],
                "companyName" => $userData['fantasyName'],
                "cnaeCode" => $userData['cnaeCode'],
                "openDate" => $userData['openDate'],
                "simpleNational" => $userData['simpleNational'],
                "companyRevenue" => $userData['companyRevenue'],
                "stateRegistration" => $userData['stateRegistration'],
                "partners" => [
                    [
                        "typePerson" => 1,
                        "isLegalRepresentative" => true,
                        "participation" => 100,
                        "legalPerson" => "",
                        "naturalPerson" => [
                            "documentNumber" => $userData['documentNumber'],
                            "name" => $userData['name'],
                            "gender" => $userData['gender'],
                            "birthDate" => $userData['birthdate'],
                            "idMaritalStatus" => $userData['idMaritalStatus'],
                            "representativeMotherName" => $userData['nameMother'],
                        ],
                        //Personal
                        "address" => [
                            "zipCode" => $partnerZipCode,
                            "address" => $partnerAddress,
                            "addressNumber" => $partnerAddressNumber,
                            "state" => $partnerState,
                            "city" => $partnerCity,
                            "neighborhood" => $partnerNeighborhood,
                            "complement" => $partnerComplement ?? '',
                        ],
                        "contact" => [
                            "email" => $userData['email'],
                            "phoneNumber" => $userData['phoneNumber'],
                            "cellPhone" => $userData['cellPhone'],
                            "commercialPhone" => $userData['commercialPhone'] ?? '',
                        ]
                    ]
                ]
            ],
            "address" => [
                "zipCode" =>   $businessZipCode,
                "addressNumber" =>  $businessAddressNumber,
                "address" =>  $businessAddress,
                "state" => $businessState,
                "city" => $businessCity,
                "neighborhood" =>  $businessNeighborhood,
                "complement" =>  $businessComplement,
            ],
            "contact" => [
                "email" => $userData['email'],
                "phoneNumber" => $userData['phoneNumber'],
                "cellPhone" => $userData['cellPhone'],
                "commercialPhone" => $userData['commercialPhone'] ?? '',
            ],
            "userAdmin" => [
                "name" => $userData['name'],
                "email" => $userData['email'],
                "username" => $userData['username'],
                "password" => 'HikSzNULrR',
            ],
            "imagecomprovante" => [
                "tipo" => "pdf",
                "base64" => $userData['cardBusiness'],
            ],
            "imagecontrato" => [
                "tipo" => "pdf",
                "base64" => $userData['imagecontrato'],
            ],
            "imagecomprovante_endereco" => [
                "tipo" => "image",
                "base64" => $userData['imagecomprovante_endereco'],
            ],
            "representante_imageself" => [
                "tipo" => "image",
                "base64" => $userData['imageself'],
            ],
            "representante_imagedoc" => [
                "tipo" => "image",
                "base64" => $userData['imagedoc'],
            ],
            "representante_imagedoc_verso" => [
                "tipo" => "image",
                "base64" => $userData['imagedoc_verso'],
            ],
            "representante_imagecomprovante_endereco" => [
                "tipo" => "image",
                "base64" => $userData['imagecomprovante'],
            ],
            "defaultWebhook" => env('MB_DEFAULT_WEBHOOK'),
            "createAPICredentials" => true,
            "confirmationUrl" => env('MB_CONFIRMATION_URL'),
        ];

        return $this->mbService->createBusiness($payload);
    }
}

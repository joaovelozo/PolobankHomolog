<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Caminho para o diretório público
        $publicPath = public_path();

        // Criar o diretório de uploads se ele não existir
        $uploadsDirectory = $publicPath . '/uploads';
        if (!File::exists($uploadsDirectory)) {
            File::makeDirectory($uploadsDirectory);
        }

        // Criar subdiretórios para documentos, selfies e comprovantes
        $documentsDirectory = $uploadsDirectory . '/documents';
        $selfiesDirectory = $uploadsDirectory . '/selfies';
        $proofsDirectory = $uploadsDirectory . '/proofs';

        if (!File::exists($documentsDirectory)) {
            File::makeDirectory($documentsDirectory);
        }
        if (!File::exists($selfiesDirectory)) {
            File::makeDirectory($selfiesDirectory);
        }
        if (!File::exists($proofsDirectory)) {
            File::makeDirectory($proofsDirectory);
        }

        // Gerar imagens fictícias e obter os caminhos
        $documentFrontPath = $this->generateFakeImage($documentsDirectory);
        $documentBackPath = $this->generateFakeImage($documentsDirectory);
        $selfiePath = $this->generateFakeImage($selfiesDirectory);
        $proofPath = $this->generateFakeImage($proofsDirectory);

        // Criar usuários
        User::insert([
            [
                'name' => 'Admin',
                'username' => 'Admnistrador',
                'nameMother' => 'Maria Senhora Velozo',
                'political' => '0',
                'profession' => 'Analista de Sistemas',
                'gender' => 'MASCULINO',
                'idMaritalStatus' => 'married',
                'phoneNumber' => '6199900000',
                'cellphone' => '61999900000',
                'documentNumber' => '12345678900',
                'identityDocument' => '1386527',
                'issuingAgency' => 'SSP',
                'issuingState' => 'DF',
                'issueDate' => date('Y-m-d', strtotime('13122023')),
                'email' => 'admin@user.com',
                'role' => 'admin',
                'password' => Hash::make('Polocal@2024!'),
                'status' => 'active',
                'address' => 'QNO 00',
                'addressNumber' => '12',
                'neighborhood' => 'Centro',
                'city' => 'São Paulo',
                'state' => 'Distrito Federal',
                'birthdate' => date('Y-m-d', strtotime('23121974')),
                'zipcode' => '721990000',
                'imagedoc' => $documentFrontPath,
                'imagedoc_verso' => $documentBackPath,
                'imageself' => $selfiePath,
                'imagecomprovante' => $proofPath,
            ],

        ]);
    }

    /**
     * Generate a fake image and return its relative path.
     *
     * @param string $directory
     * @return string
     */
    private function generateFakeImage(string $directory): string
    {
        $fileName = 'fake_image_' . uniqid() . '.jpg';
        $filePath = $directory . '/' . $fileName;

        // Criar uma imagem preta simples de 200x200 px
        $image = imagecreatetruecolor(200, 200);
        $backgroundColor = imagecolorallocate($image, 0, 0, 0); // preto
        imagefill($image, 0, 0, $backgroundColor);

        // Salvar imagem
        imagejpeg($image, $filePath);
        imagedestroy($image);

        // Retornar caminho relativo para uso na aplicação
        return 'uploads/' . basename($directory) . '/' . $fileName;
    }
}

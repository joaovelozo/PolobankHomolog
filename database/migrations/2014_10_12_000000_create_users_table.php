<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username');
            $table->enum('political', ['0', '1'])->default('0');
            $table->string('avatar')->default('/uploads/noimage.jpg');
            $table->string('email')->unique();
            $table->string('phoneNumber');
             $table->string('cellphone');
            $table->string('documentNumber')->unique();
            $table->string('identityDocument')->unique(); //Número de Identidade
            $table->date('issueDate');// Data de Emissão
            $table->string('issuingAgency');//Orgão Emissor
            $table->string('issuingState');//Estado emissor
            $table->string('nameMother');//Nome da Mãe
            $table->string('profession')->nullable();
            $table->date('birthdate');
            $table->enum('gender', ['MASCULINO', 'FEMININO','OUTROS'])->nullable();
            $table->enum('idMaritalStatus', ['married', 'separate','single','widower'])->nullable();
            $table->enum('role',['user','admin', 'manager'])->default('user');
            $table->enum('status',['active','inactive'])->default('inactive');
            $table->longText('imageself');
            $table->longText('imagedoc');
            $table->longText('imagedoc_verso');
            $table->longText('imagecomprovante');
            $table->string('address');
            $table->string('addressNumber');
            $table->string('state');
            $table->string('zipCode');
            $table->string('complement')->nullable();
            $table->string('neighborhood');
            $table->string('city');
            $table->string('rent')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->timestamp('last_login')->nullable();

            //Account
            $table->string('account_id')->nullable();
            $table->string('document')->nullable();
            $table->string('client_id')->nullable();
            $table->string('client_secret')->nullable();
            $table->string('application_token')->nullable();
            $table->string('crypto_token')->nullable();

            //Bussines Account
            $table->string('businessZipCode')->nullable();
            $table->string('businessAddressNumber')->nullable();
            $table->string('businessAddress')->nullable();
            $table->string('businessState')->nullable();
            $table->string('businessCity')->nullable();
            $table->string('businessNeighborhood')->nullable();
            $table->string('businessComplement')->nullable();
            $table->string('documentBusiness')->nullable()->unique();
            $table->string('fantasyName')->nullable();
            $table->string('companyName')->nullable();
            $table->string('companyRevenue')->nullable();
            $table->string('cnaeCode')->nullable();
            $table->date('openDate')->nullable();
            $table->string('stateRegistration')->nullable();
            $table->string('cardBusiness')->nullable();
            $table->string('imagecontrato')->nullable();
            $table->string('imagecomprovante_endereco')->nullable();
            $table->enum('simpleNational', ['MEI','ME', 'EI','LTDA','S/A','SS','SLU','EIRELI'])->nullable();


            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

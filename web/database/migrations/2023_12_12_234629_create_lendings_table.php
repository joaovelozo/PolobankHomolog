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
        Schema::create('lendings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); 
            $table->foreign('user_id')->references('id')->on('users'); 
            $table->string('name');
            $table->string('phone');
            $table->string('cpf');
            $table->string('email');
            $table->decimal('amount', 10, 2);
            $table->unsignedInteger('installments');
            $table->enum('status', ['assinar contrato','aprovado', 'negado','em analise','enviar documentos'])->default('em analise');
            $table->text('message')->nullable();
            $table->string('url')->nullable();
            $table->string('document')->nullable();
            $table->string('proof')->nullable();
            $table->string('invoice')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lendings');
    }
};

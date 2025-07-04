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
        Schema::table('users', function (Blueprint $table) {
            // Adiciona a coluna agency_id
            $table->unsignedBigInteger('agency_id')->nullable()->after('email');

            // Opcional: adicionar uma chave estrangeira referenciando a tabela agencies
            $table->foreign('agency_id')->references('id')->on('agencies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
             // Remove a chave estrangeira, se adicionada
             $table->dropForeign(['agency_id']);
            
             // Remove a coluna
             $table->dropColumn('agency_id');
        });
    }
};

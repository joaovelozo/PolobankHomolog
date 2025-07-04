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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investment_id')->constrained()->onDelete('cascade');
            $table->foreignId('type_id')->constrained('types');
            $table->string('title');
            $table->text('description');
            $table->decimal('amount', 10, 2); // Adicionando o campo para o valor investido
            $table->string('term'); // Duração do investimento
            $table->decimal('tax', 5, 2); // Taxa de juros
            $table->decimal('performance', 5, 2); // Desempenho
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};

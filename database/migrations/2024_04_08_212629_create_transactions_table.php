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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('token')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('cpfcnpj')->nullable();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('document_number')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('branch_code')->nullable();
            $table->string('account_number')->nullable();
            $table->string('description')->nullable();
            $table->enum('operacao', ['CREDIT', 'DEBIT']);
            $table->enum('status', ['CREATED', 'PROCESSING', 'SUCCESS', 'FAILED', 'CANCELED', 'EXPIRED', 'REFUNDED']);
            $table->enum('type', ['INVOICE', 'DYNAMIC_BRCODE', 'DEPOSIT', 'BOLETO', 'TRANSFER', 'BRCODE_PAYMENT', 'BOLETO_PAYMENT', 'UTILITY_PAYMENT']);
            $table->enum('method', ['PIX', 'BOLETO', 'TRANSFER']);
            $table->decimal('fee', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

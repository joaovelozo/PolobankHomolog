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
        Schema::create('telemedicines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('telemed_plan_id')->constrained()->onDelete('cascade');
            $table->string('document');
            $table->date('birthdate');
            $table->string('name');
            $table->string('celphone');
            $table->string('zipcode');
            $table->string('address');
            $table->string('number');
            $table->string('complement')->nullable();
            $table->string('neighborhood');
            $table->string('city');
            $table->string('state');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telemedicines');
    }
};

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
        Schema::table('admin_ivestments', function (Blueprint $table) {
            $table->date('redemption_date')->nullable();   // Data de término do investimento
         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_ivestments', function (Blueprint $table) {
            $table->dropColum('redemption_date');
        });
    }
};

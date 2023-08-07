<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Adicione as colunas id_pais, id_estado e id_cidade do tipo int e nullable
            $table->unsignedBigInteger('id_pais')->nullable();
            $table->unsignedBigInteger('id_estado')->nullable();
            $table->unsignedBigInteger('id_cidade')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Desfaça as alterações no método down, se necessário
            $table->dropColumn(['id_pais', 'id_estado', 'id_cidade']);
        });
    }
};
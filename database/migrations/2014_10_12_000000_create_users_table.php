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
            $table->string('nome');
            $table->string('sobrenome');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('senha');
            $table->boolean('flg_ativo')->default(1);
            $table->string('imagem')->nullable();
            $table->string('rua_endereco');
            $table->string('numero_endereco');
            $table->string('complemento_endereco')->nullable();
            $table->string('bairro_endereco');
            $table->string('estado_endereco');
            $table->string('cidade_endereco');
            $table->string('cpf')->nullable();
            $table->string('cnpj')->nullable();
            $table->string('telefone');
            $table->boolean('telefone_is_whatsapp')->default(0);
            $table->string('user_tipo')->default("user");
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

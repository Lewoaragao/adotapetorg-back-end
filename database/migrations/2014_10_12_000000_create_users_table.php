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
            $table->string('usuario')->unique();
            $table->boolean('is_pessoa')->default(1);
            $table->string('primeiro_nome')->nullable();
            $table->string('sobrenome')->nullable();
            $table->string('sigla_organizacao')->nullable();
            $table->string('nome_organizacao')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('senha');
            $table->boolean('flg_ativo')->default(1);
            $table->string('imagem')->nullable();
            $table->string('telefone')->nullable();
            $table->boolean('flg_telefone_whatsapp')->default(0);
            $table->string('celular')->nullable();
            $table->boolean('flg_celular_whatsapp')->default(0);
            $table->string('user_tipo')->default("user");
            $table->string('link');
            $table->string('endereco_cidade');
            $table->string('endereco_estado');
            $table->string('endereco_pais');
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

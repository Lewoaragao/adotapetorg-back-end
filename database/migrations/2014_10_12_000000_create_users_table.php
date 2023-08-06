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
            $table->string('senha')->nullable();
            $table->boolean('flg_ativo')->default(1);
            $table->string('imagem')->nullable();
            $table->string('telefone')->nullable();
            $table->boolean('flg_telefone_whatsapp')->default(0);
            $table->string('celular')->nullable();
            $table->boolean('flg_celular_whatsapp')->default(0);
            $table->string('user_tipo')->default("user");
            $table->string('link');
            $table->integer('id_pais')->nullable();
            $table->string('endereco_pais')->nullable();
            $table->integer('id_estado')->nullable();
            $table->string('endereco_estado')->nullable();
            $table->integer('id_cidade')->nullable();
            $table->string('endereco_cidade')->nullable();
            $table->string('google_id')->unique()->nullable();
            $table->string('facebook_id')->unique()->nullable();
            $table->string('github_id')->unique()->nullable();
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
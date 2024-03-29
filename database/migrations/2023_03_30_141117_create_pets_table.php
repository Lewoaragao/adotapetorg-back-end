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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nome');
            $table->unsignedBigInteger('pet_tipos_id')->default(1);
            $table->unsignedBigInteger('raca_id')->default(1);
            $table->date('data_nascimento');
            $table->boolean('flg_adotado')->default(0);
            $table->string('imagem')->nullable();
            $table->date('data_adocao')->nullable();
            $table->boolean('flg_ativo')->default(1);
            $table->string('apelido')->nullable();
            $table->string('tamanho')->nullable();
            $table->boolean('flg_necessidades_especiais')->default(0);
            $table->string('necessidades_especiais')->nullable();
            $table->string('sexo');
            $table->timestamps();

            // FOREIGN KEY
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('raca_id')->references('id')->on('racas');
            $table->foreign('pet_tipos_id')->references('id')->on('pet_tipos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};

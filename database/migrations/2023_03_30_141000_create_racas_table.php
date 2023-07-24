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
        Schema::create('racas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pet_tipos_id')->default(1);
            $table->string('raca');
            $table->timestamps();

            // FOREIGN KEY
            $table->foreign('pet_tipos_id')->references('id')->on('pet_tipos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('racas');
    }
};

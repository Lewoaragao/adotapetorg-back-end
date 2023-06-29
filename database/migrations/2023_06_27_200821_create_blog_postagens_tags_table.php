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
        Schema::create('blog_postagens_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blog_postagens_id');
            $table->unsignedBigInteger('blog_tags_id');
            $table->timestamps();

            // FOREIGN KEY
            $table->foreign('blog_postagens_id')->references('id')->on('blog_postagens');
            $table->foreign('blog_tags_id')->references('id')->on('blog_tags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_postagens_tags');
    }
};
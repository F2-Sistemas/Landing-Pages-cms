<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->integer('audit_form_list_id');
            $table->foreign('audit_form_list_id')->references('id')->on('audit_form_lists');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('public.users');
            $table->uuid('pergunta_uuid');
            $table->string('resposta_type')->nullable();
            $table->longText('resposta')->nullable();
            $table->string('comentario')->nullable();
            $table->longText('images')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};

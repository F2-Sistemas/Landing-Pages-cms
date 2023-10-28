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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->integer('audit_form_list_id');
            $table->foreign('audit_form_list_id')->references('id')->on('audit_form_lists');
            $table->uuid('pergunta_uuid')->nullable();

            $table->text('acao')->nullable();
            $table->string('responsavel')->nullable();
            $table->date('inicio_p')->nullable();
            $table->date('termino_p')->nullable();
            $table->date('inicio_r')->nullable();
            $table->date('termino_r')->nullable();
            $table->text('observacao')->nullable();
            $table->string('onde')->nullable();
            $table->json('etapas')->nullable();
            $table->integer('status_id')->nullable();
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->integer('action_type_id')->nullable();
            $table->foreign('action_type_id')->references('id')->on('action_types');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};

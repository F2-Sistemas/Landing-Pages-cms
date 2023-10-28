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
        Schema::create('nonconformities', function (Blueprint $table) {
            $table->id();
            $table->integer('audit_form_list_id');
            $table->foreign('audit_form_list_id')->references('id')->on('audit_form_lists');
            $table->uuid('pergunta_uuid');
            $table->text('pergunta');

            $table->text('recomendacao');
            $table->string('referencia')->nullable();
            $table->date('prazo')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nonconformities');
    }
};

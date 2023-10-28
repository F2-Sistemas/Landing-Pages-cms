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
        Schema::create('audit_form_lists', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->integer('content_tree_id')->nullable();
            $table->foreign('content_tree_id')->references('id')->on('content_trees');
            $table->integer('audit_form_id');
            $table->foreign('audit_form_id')->references('id')->on('audit_forms');
            $table->date('data_inicio')->nullable();
            $table->date('data_termino')->nullable();
            $table->boolean('respondido')->nullable()->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_form_lists');
    }
};

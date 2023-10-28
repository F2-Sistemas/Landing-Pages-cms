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
        Schema::create('form_option_lists', function (Blueprint $table) {
            $table->id();
            $table->integer('form_option_id');
            $table->foreign('form_option_id')->references('id')->on('form_options');
            $table->string('opcao');
            $table->integer('conforme')->nullable();
            $table->integer('ordem')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_option_lists');
    }
};

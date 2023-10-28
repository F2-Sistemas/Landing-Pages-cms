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
        Schema::create('content_trees', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->integer('pai_id')->nullable();
            $table->integer('tree_id');
            $table->foreign('tree_id')->references('id')->on('trees');
            $table->string('uuid')->nullable();
            $table->string('uuid_pai')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_trees');
    }
};

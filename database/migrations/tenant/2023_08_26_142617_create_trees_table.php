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
        Schema::create('trees', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->integer('provider_codigo')->nullable();
            $table->foreign('provider_codigo')->references('codigo')->on('public.providers');
            $table->integer('city_codigo')->nullable();
            $table->foreign('city_codigo')->references('codigo')->on('public.cities');
            $table->integer('service_id')->nullable();
            $table->foreign('service_id')->references('id')->on('services');
            $table->json('content_tree')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trees');
    }
};

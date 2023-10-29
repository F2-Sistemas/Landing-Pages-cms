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
        Schema::create('public.pages', function (Blueprint $table) {
            $table->string('id')->primary()->index();
            $table->string('title')->nullable()->index();
            $table->string('slug')->nullable()->index();
            $table->string('tenant_id')->nullable()->index();
            $table->string('view')->nullable();
            $table->boolean('only_auth')->nullable()->index()->default(false);
            $table->boolean('published')->nullable()->index()->default(true);
            $table->json('data')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('created_at');
            $table->index('updated_at');
            $table->index('deleted_at');
            $table->unique(['slug', 'tenant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public.pages');
    }
};

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
        Schema::table('public.tenants', function (Blueprint $table) {
            $table->string('name')->nullable()->index();
            $table->string('logo')->nullable();
            $table->integer('city_codigo')->nullable()->index();
            $table->foreign('city_codigo')->references('codigo')->on('public.cities')->onDelete('set null');

            $table->softDeletes();
            $table->index('created_at');
            $table->index('updated_at');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('public.tenants', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['updated_at']);
            $table->dropIndex(['deleted_at']);

            $table->dropSoftDeletes();
            $table->dropForeign(['city_codigo']);
            $table->dropColumn([
                'name',
                'logo',
                'city_codigo',
            ]);
        });
    }
};

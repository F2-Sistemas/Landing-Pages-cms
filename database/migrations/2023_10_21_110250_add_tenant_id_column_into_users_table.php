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
        Schema::table('public.users', function (Blueprint $table) {
            $table->string('tenant_id')->index()->nullable();

            $table->foreign('tenant_id')->references('id')
                ->on('tenants')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('public.users', function (Blueprint $table) {
            $table->dropIndex('tenant_id_index');
            $table->dropColumn('tenant_id');
        });
    }
};

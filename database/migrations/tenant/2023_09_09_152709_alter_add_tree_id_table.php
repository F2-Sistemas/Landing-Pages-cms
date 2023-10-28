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
        Schema::table('audit_form_lists', function (Blueprint $table) {
            $table->integer('tree_id')->nullable();
            $table->foreign('tree_id')->references('id')->on('trees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_form_lists', function (Blueprint $table) {
            $table->dropColumn('tree_id');
        });
    }
};

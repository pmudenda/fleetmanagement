<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('GEN_MATERIAL_HEADERS', function (Blueprint $table) {
            $table->string('cost_assigned_to')->nullable()->add();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_header', function (Blueprint $table) {
            //
        });
    }
};

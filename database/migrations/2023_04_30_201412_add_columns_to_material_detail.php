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
        Schema::table('GEN_MATERIAL_DETAILS', function (Blueprint $table) {
            $table->string('cost_centre_name', 15)->nullable()->add();
            $table->string('project_name', 15)->nullable()->add();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_detail', function (Blueprint $table) {
            //
        });
    }
};

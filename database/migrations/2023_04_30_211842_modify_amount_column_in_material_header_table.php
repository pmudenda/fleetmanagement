<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('GEN_MATERIAL_HEADERS', function (Blueprint $table) {
            $table->string('cost_assigned_to', 20)->nullable()->change();
        });


        Schema::table('GEN_MATERIAL_DETAILS', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->change();
            $table->decimal('price', 18, 2)->change();
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

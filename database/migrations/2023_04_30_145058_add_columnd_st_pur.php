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

            $table->string('st_pur')->nullable()->add();
            $table->Integer('odometer')->nullable()->add();
            $table->string('cost_centre', 15)->nullable()->add();
            $table->string('item_type')->nullable()->add();
            $table->string('workshop_no', 255)->nullable()->add();
            $table->string('document_no', 255)->nullable()->add();
            $table->string('form_order')->nullable()->add();
            $table->string('requested_by',255)->nullable()->add();
            $table->string('authorised_by',255)->nullable()->add();
            $table->string('town_from')->nullable()->add();
            $table->string('town_to')->nullable()->add();
        });

        Schema::table('GEN_MATERIAL_DETAILS', function (Blueprint $table) {
            $table->string('cost_centre', 15)->nullable()->add();
            $table->string('stores_code', 15)->nullable()->add();
            $table->string('movt_no', 15)->nullable()->add();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

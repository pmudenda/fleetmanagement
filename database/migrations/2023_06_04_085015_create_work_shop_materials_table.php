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
        Schema::create('WM_WORKSHOP_MATERIALS', function (Blueprint $table) {
            $table->id();

            // $table->string('workshop_reference')->nullable();
            $table->string('wshp_act_code')->nullable();
            $table->string('workshop_code', 20)->nullable();
            $table->string('section', 10)->nullable();
            // $table->string('req_evaluation', 2)->nullable();
            $table->string('evaluation', 2)->nullable();
            $table->timestamp('date_mat')->nullable();
            // $table->string('material_code')->nullable();
            $table->string('mat_code')->nullable();
            $table->string('unit_of_measure', 18)->nullable();
            $table->Integer('quantity')->nullable();

            $table->decimal('amount', 18, 2)->nullable();
            $table->decimal('price', 18, 2)->nullable();

            $table->string('defect_no', 255)->nullable();
            $table->string('specifications', 2000)->nullable();
            $table->string('proc_ref')->nullable();
            $table->string('st_pur')->nullable();
            $table->string('form_order')->nullable();
            $table->string('store_code', 15)->nullable();

            $table->string('ind', 2)->nullable();
            $table->string('sch_flouted', 2)->nullable();
            $table->string('supplier_code', 10)->nullable();

            $table->string('veh_reg_no')->nullable()->nullable();
            $table->string('requested_by', 15)->nullable();
            $table->string('requested_by_id', 13)->nullable();
            $table->string('authorised_by', 15)->nullable();

            $table->string('status', 4)->nullable();
            $table->string('created_by', 10);
            $table->string('modified_by', 10)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('WM_WORKSHOP_MATERIALS');
    }
};

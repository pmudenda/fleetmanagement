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

            $table->string('workshop_reference')->nullable();
            $table->string('workshop_code', 20);
            $table->string('section', 10);
            $table->string('req_evaluation', 2);
            $table->timestamp('date_mat');
            $table->string('material_code');
            $table->string('unit_of_measure');
            $table->Integer('quantity');

            $table->decimal('amount', 18, 2);
            $table->decimal('price', 18, 2);

            $table->string('def_no', 255)->nullable();
            $table->string('proc_ref')->nullable();
            $table->string('st_pur')->nullable();
            $table->string('form_order')->nullable();
            $table->string('store_code', 15)->nullable();

            $table->string('ind', 2);
            $table->string('sch_flouted', 2);
            $table->string('supplier_code', 10);

            $table->string('veh_reg_no')->nullable();
            $table->string('requested_by', 15)->nullable();
            $table->string('requested_by_id', 13)->nullable();
            $table->string('authorised_by', 15)->nullable();

            $table->string('status', 4)->nullable();
            $table->string('created_by', 10);
            $table->string('modified_by', 10)->nullable();

            $table->timestamps();

            //$table->string('cost_assigned_to', 20)->nullable()->change();
            //$table->string('town_to')->nullable();
            //$table->string('comments')->nullable();
            //$table->string('requisition_status', 20);
            //$table->string('cost_assigned_to')->nullable();
            //$table->string('requisition_type')->nullable();
            //$table->timestamp('valid_date_from')->nullable();
            //$table->timestamp('valid_date_to')->nullable();
            //$table->Integer('odometer')->nullable();
            //$table->string('item_type')->nullable();
            //$table->string('workshop_no', 255)->nullable();
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

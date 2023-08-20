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
        Schema::create('WM_WORKSHOP_SERVICES', function (Blueprint $table) {
            $table->id();

            $table->string('workshop_reference')->nullable();
            //$table->string('workshop_code', 20);
            $table->string('wshp_act_code', 20);
            $table->string('section', 10)->nullable();
            $table->string('evaluation', 2);
            $table->timestamp('date_send')->nullable();
            $table->string('supp_code', 20);
            $table->string('supplier_name', 20)->nullable();
            $table->timestamp('date_collect')->nullable();
            $table->string('unit_of_measure', 4);
            $table->decimal('price', 19, 4);
            $table->decimal('amount_est', 19, 4);
            $table->string('def_no', 10)->nullable();
            $table->string('code_office', 10)->nullable();
            $table->string('specifications', 1000)->nullable();
            $table->string('ind', 2)->nullable();
            $table->string('mat_code', 20);
            $table->Integer('quantity');
            $table->string('stf_number', 20)->nullable();

            //$table->string('movement_no', 20)->nullable();
            $table->string('movt_no', 20)->nullable();
            $table->string('status', 4)->nullable();

            $table->string('originator', 15);
            $table->string('requested_by_id', 13)->nullable();
            $table->string('authorised_by', 15)->nullable();

            //$table->string('proc_ref', 20)->nullable();
            //$table->string('st_pur', 20)->nullable();
            //$table->string('form_order', 20)->nullable();

            $table->string('created_by', 10);
            $table->string('user1', 10);
            $table->string('modified_by', 10)->nullable();
            $table->timestamp('fech_act' )->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('WM_WORKSHOP_SERVICES');
    }
};

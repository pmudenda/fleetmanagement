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
        Schema::create('SM_MOVEMENT_HEADER', function (Blueprint $table) {
            $table->id();
            $table->string('created_by')->nullable();
            $table->timestamp('created_date');
            $table->string('document_number');
            $table->string('expense_type');
            $table->string('transaction_type');
            $table->string('veh_reg_no')->nullable();
            $table->date('movement_date');
            $table->string('store_code', 20)->nullable();
            $table->string('business_area', 255);
            $table->string('cost_centre', 255);
            $table->string('work_order_no', 255);
            $table->string('stf_number', 255)->nullable();
            $table->string('requested_by')->nullable();
            $table->string('system_of_origin');
            $table->string('requisition_no');
            $table->string('stores_resrv_no')->nullable();
            $table->string('delivery_site')->nullable();
            $table->string('subject')->nullable();
            $table->string('business_unit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movement_header');
    }
};

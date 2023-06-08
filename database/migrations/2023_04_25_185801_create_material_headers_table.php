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
        Schema::create('GEN_MATERIAL_HEADERS', function (Blueprint $table) {
            $table->id();
            $table->string('proc_ref')->nullable();
            $table->string('req_no');
            $table->string('veh_reg_no')->nullable();
            $table->timestamp('valid_date_from')->nullable();
            $table->timestamp('valid_date_to')->nullable();
            $table->date('date_created');
            $table->string('created_by');
            $table->string('st_pur')->nullable();
            $table->Integer('odometer')->nullable();
            $table->string('cost_centre', 15)->nullable();
            $table->string('purchase_office', 15)->nullable();
            $table->string('store', 15)->nullable();
            $table->string('item_type')->nullable();
            $table->string('workshop_no', 255)->nullable();
            $table->string('document_no', 255)->nullable();
            $table->string('form_order',20)->nullable();
            $table->string('requested_by',255)->nullable();
            $table->string('authorised_by',255)->nullable();
            $table->string('town_from')->nullable();
            $table->string('town_to')->nullable();
            $table->string('comments')->nullable();
            $table->string('status')->nullable();
            $table->string('requisition_status', 20);
            $table->string('cost_assigned_to')->nullable();
            $table->string('requested_by_id',13)->nullable();
            $table->string('requisition_type')->nullable();
            $table->string('cost_assigned_to', 20)->nullable()->change();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('GEN_MATERIAL_HEADERS');
    }
};

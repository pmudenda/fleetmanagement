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
        Schema::create('VM_ASSIGNMENTS', function (Blueprint $table) {
            $table->id();
            $table->string('directorate');
            $table->string('isPoolVehicle');
            $table->string('isTeamAssigned')->nullable();
            $table->string('mileageExempt');
            $table->string('operatorStaffNumber')->nullable();

            $table->string('vehicle_header_id');
            $table->integer('created_by');
            $table->string('created_name');
            $table->string('vehicleHolderName')->nullable();
            $table->string('vehicleHolder')->nullable();

            $table->string('cost_center', 15);
            $table->string('business_unit')->nullable();
            $table->string('cost_center_name', 255);
            $table->string('business_unit_name', 255)->nullable();
            $table->string('directorate_name', 255)->nullable();
            $table->string('responsible_head_id', 15)->nullable();
            $table->string('responsible_head_name', 255)->nullable();
            $table->string('business_area_code', 15);
            $table->string('business_area_name', 255);
            $table->string('assignment_state')->nullable();
            $table->timestamps();

            //$table->string('businessArea');
            //$table->string('casualStaffNumber');
            //$table->string('casualStaffName');
            //$table->string('casualStaffName')->nullable();
            //$table->string('casualStaffNumber')->nullable()->change();
            //$table->string('costCenter');
            //$table->string('operatorName');
            //$table->string('operatorStaffNumber');
            //$table->string('operatorStaffNumber')->nullable()->change();
            //$table->string('superVisorName');
            //$table->string('superVisorStaffNumber');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('VM_ASSIGNMENTS');
    }
};

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

            $table->string('businessArea');
            $table->string('casualStaffNumber');
            $table->string('casualStaffName');
            $table->string('costCenter');
            $table->string('directorate');
            $table->string('isPoolVehicle');
            $table->string('isTeamAssigned');
            $table->string('mileageExempt');
            $table->string('operatorName');
            $table->string('operatorStaffNumber');
            $table->string('superVisorName');
            $table->string('superVisorStaffNumber');


            $table->string('vehicle_header_id');
            $table->integer('created_by');
            $table->string('created_name');
            $table->timestamps();

            //$table->dropColumn('businessunit');
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

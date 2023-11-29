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
            $table->string('reg_no', 10)->nullable();
            $table->string('vehicle_header_id');

            $table->string('businessArea');
            $table->string('costCenter');
            $table->string('directorate');

            $table->string('responsible_head_id', 10);
            $table->string('responsible_head_name', 100);

            $table->string('casualStaffNumber')->nullable();
            $table->string('casualStaffName')->nullable();
            $table->string('isPoolVehicle')->nullable();
            $table->string('isTeamAssigned')->nullable();
            $table->string('mileageExempt')->nullable();
            $table->string('operatorName')->nullable();
            $table->string('operatorStaffNumber')->nullable();

            $table->string('superVisorName')->nullable();
            $table->string('superVisorStaffNumber')->nullable();
            $table->string('created_by', 10)->nullable();
            $table->string('created_name', 191)->nullable();
            $table->timestamps();
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

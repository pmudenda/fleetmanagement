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

        Schema::table('VM_ASSIGNMENTS', function (Blueprint $table) {

            $table->dropColumn([
                'casualStaffNumber',
                'casualStaffName',
                'operatorName',
                'superVisorStaffNumber',
                'superVisorName',
                'businessArea',
                'costCenter'
            ]);

            $table->string('cost_center', 15)->add();
            $table->string('isTeamAssigned')->nullable()->change();

            $table->string('business_unit')->nullable()->add();
            $table->string('cost_center_name', 255)->add();
            $table->string('business_unit_name', 255)->nullable()->add();
            $table->string('directorate_name', 255)->nullable()->add();

            $table->string('responsible_head_id', 15)->nullable()->add();
            $table->string('responsible_head_name', 255)->nullable()->add();
            $table->string('business_area_code', 15)->add();
            $table->string('business_area_name', 255)->add();
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

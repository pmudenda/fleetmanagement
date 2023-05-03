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
        Schema::table('VM_ASSIGNMENTS', function (Blueprint $table) {
            //
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up(): void
    {
        Schema::table('SEC_USERS', function (Blueprint $table) {
            $table->string('supervisor_code',255)->nullable()->add();
            $table->string('group_type',255)->nullable()->add();
            $table->string('job_title',255)->nullable()->add();
            $table->string('location',255)->nullable()->add();
            $table->string('grade',255)->nullable()->add();
            $table->string('directorate',255)->nullable()->add();
            $table->string('functional_section',255)->nullable()->add();
            $table->string('pay_point',255)->nullable()->add();
            $table->string('bu_code',255)->nullable()->add();
            $table->string('cc_code',255)->nullable()->add();
            $table->string('mobile_no',255)->nullable()->add();
            $table->dropColumn([
                'grade_id',
                'unit_column',
                'code_column',
                'supervisor_guid',
                'location_id',
                'positions_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('SEC_USERS', function (Blueprint $table) {
            //
        });
    }
};

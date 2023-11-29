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
        Schema::create('wm_mechanics', function (Blueprint $table) {
            $table->id();
            $table->string('staff_no', 10);
            $table->string('name', 255);
            $table->string('is_supervisor', 2)->nullable();
            $table->string('workshop_code', 4)->nullable();
            $table->string('section_code', 4)->nullable();
            $table->string('status', 2);
            $table->string('email')->unique();


            $table->string('extension')->unique();
            $table->string('area_code',4)->nullable();
            $table->string('functional_section',255)->nullable();
            $table->string('bu_code',20)->nullable();
            $table->string('cc_code',20)->nullable();
            $table->string('directorate',200)->nullable();
            $table->string('user_unit',255)->nullable();
            $table->string('contract_type', 90)->nullable();
            $table->string('nrc', 18)->nullable();
            $table->string('mobile_no',25)->nullable();
            $table->string('group_type',55)->nullable();
            $table->string('job_title',255)->nullable();
            $table->string('grade',4)->nullable();
            $table->string('location',255)->nullable();
            $table->string('pay_point',255)->nullable();
            $table->string('job_code',50)->nullable();

            $table->string('created_by', 10)->nullable();
            $table->string('modified_by', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wm_mechanics');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('SEC_USERS', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contract_type')->nullable();
            $table->string('con_st_code')->nullable();
            $table->string('nrc')->nullable();

            $table->string('avatar')->nullable();
            $table->string('phone')->nullable();

            $table->string('username')->unique();
            $table->string('staff_no')->unique()->nullable();

            $table->string('user_unit_code')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('user_region_id')->nullable();
            $table->integer('functional_unit_id')->nullable();
            $table->string('user_unit',255)->nullable();
            $table->string('two_fac_auth_status')->nullable()
                ->default('Inactive');

            $table->dateTime('last_login')->nullable();
            $table->integer('type_id')->default('0');
            $table->integer('total_logins')->default(0);
            $table->integer('password_changed')->default('0');
            $table->string('supervisor_code',255)->nullable();
            $table->string('supervisor_name', 200)->nullable();
            $table->string('group_type',255)->nullable();
            $table->string('job_title',255)->nullable();
            $table->string('location',255)->nullable();
            $table->string('grade',255)->nullable();
            $table->string('directorate',255)->nullable();
            $table->string('functional_section',255)->nullable();
            $table->string('pay_point',255)->nullable();
            $table->string('bu_code',255)->nullable();
            $table->string('cc_code',255)->nullable();
            $table->string('mobile_no',255)->nullable();
            $table->string('area_code',4)->nullable()->add();
            $table->string('guid')->default(Str::uuid())->nullable();
            //$table->string('job_code')->nullable();
            //$table->string('profile_job_code')->nullable();
            //$table->string('profile_unit_code')->nullable();
            //$table->string('unit_column')->nullable(); // dropped
            //$table->string('code_column')->nullable(); // dropped
            //$table->integer('user_unit_id')->nullable();
            //$table->integer('user_directorate_id')->nullable();
            //$table->integer('user_division_id')->nullable();
            //$table->integer('location_id')->nullable();
            //$table->integer('pay_point_id')->nullable();
            //$table->string('supervisor_guid',150)->nullable();
            //dropped, replaced by supervisor_code
            //$table->integer('grade_id')->default('0'); //dropped
            //$table->integer('positions_id')->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();


            /*$table->dropColumn([
                'grade_id',
                'unit_column',
                'code_column',
                'supervisor_guid',
                'location_id',
                'positions_id'
            ]);*/

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('SEC_USERS');
    }
};

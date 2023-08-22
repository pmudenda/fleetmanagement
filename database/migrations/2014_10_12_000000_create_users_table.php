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
            $table->timestamp('change_password_on')->nullable();
            $table->string('password');
            $table->string('work_shop_code', 4)->nullable();
            $table->integer('functional_unit_id')->nullable();
            $table->string('user_unit',255)->nullable();
            $table->string('two_fac_auth_status')->nullable()
                ->default('Inactive');

            $table->dateTime('last_login')->nullable();
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
            $table->string('profile_code', 191)->nullable();
            $table->string('profile_name', 191)->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
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

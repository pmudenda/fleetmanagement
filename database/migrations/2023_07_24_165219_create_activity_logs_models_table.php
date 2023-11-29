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
        Schema::create('SEC_AUDIT_LOG', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('staff_no')->nullable();
            $table->string('username')->nullable();
            $table->string('user_email')->nullable();

            $table->string('ip_address')->nullable();
            $table->string('route_url')->nullable();
            $table->string('previous_url')->nullable();
            $table->string('request_method')->nullable();
            $table->string('request_params')->nullable();

            $table->string('action_name')->nullable();
            $table->string('action_type')->nullable();
            $table->string('comment')->nullable();

            $table->string('device')->nullable();
            $table->string('device_type')->nullable();
            $table->string('os')->nullable();
            $table->string('os_version')->nullable();
            $table->string('browser')->nullable();
            $table->string('browser_version')->nullable();

            $table->string('iso_code')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('state_name')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('timezone')->nullable();
            $table->string('continent')->nullable();
            $table->string('currency')->nullable();
            $table->string('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs_models');
    }
};

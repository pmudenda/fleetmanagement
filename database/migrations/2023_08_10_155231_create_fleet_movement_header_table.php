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
        Schema::create('vm_fleet_movement_header', function (Blueprint $table) {
            $table->id();

            $table->date('period_from');
            $table->date('period_to');

            $table->string('odometer_start')->nullable();
            $table->string('odometer_end')->nullable();
            $table->string('odometer_diff')->nullable();

            $table->string('business_area', 2);
            $table->string('cost_center', 20);

            $table->string('reg_no', 10);
            $table->string('logged_by', 20);
            $table->string('serial_no', 20);
            $table->string('batch_no', 20)->nullable();

            $table->string('hours_start', 20)->nullable();
            $table->string('hours_end', 20)->nullable();
            $table->string('hours_done', 20)->nullable();

            $table->string('authorised_by', 20)->nullable();
            $table->date('auth_date')->nullable();

            $table->string('driver', 10)->nullable();
            $table->string('driver_name', 255)->nullable();

            $table->string('source')->default('Manual');
            $table->string('auto_void_reason', 200)->nullable();

            $table->string('int_order', 20)->nullable();

            $table->string('accounted', 2)->default('N');
            $table->date('acc_date')->nullable();
            $table->string('machine_type', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vm_fleet_movement_header');
    }
};

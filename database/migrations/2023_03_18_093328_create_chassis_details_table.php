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
        Schema::create('VM_CHASSIS_DETAILS', function (Blueprint $table) {
            $table->id();
            $table->string('chassis_number', 100);
            $table->date('date_on_road');
            $table->string('engine_number', 100);
            $table->integer('initial_odometer_reading');
            $table->integer('current_odometer_reading');
            $table->date('inspection_date');
            $table->integer('lst_service_odometer_reading');
            $table->integer('nxt_service_odometer_reading');
            $table->boolean('odometer_reset');
            $table->date('registration_date');
            $table->string('min_req_driving_license', 2);
            $table->string('status', 100);
            $table->string('sticker_registration_number');
            $table->string('vehicle_charge_out_rate');
            $table->string('white_book_serial');
            $table->integer('year_of_manufacture');
            $table->integer('created_by');
            $table->string('created_name')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->string('vehicle_header_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('VM_CHASSIS_DETAILS');
    }
};

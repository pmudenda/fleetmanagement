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
        Schema::create('VM_ENGINE_DETAILS', function (Blueprint $table) {
            $table->id();
            $table->string('actual_engine_power', 100)->nullable();
            $table->string('claimed_engine_power', 100)->nullable();
            $table->string('engine_brand', 100)->nullable();
            $table->string('engine_capacity', 100)->nullable();
            $table->string('engine_type', 100)->nullable();
            $table->string('fuel_allocation', 100)->nullable()->default(0);
            $table->string('fuel_consumption', 100)->nullable();
            $table->string('fuel_types', 100)->nullable();
            $table->integer('number_of_cylinders')->nullable();
            $table->string('tank_capacity', 100);
            $table->string('sub_tank_capacity', 100)->nullable()->default(0);
            $table->string('transmission_type', 100)->nullable();
            $table->string('reg_no', 10)->nullable();

            $table->string('battery_brand', 100)->nullable();;
            $table->string('battery_size', 100)->nullable();
            $table->string('battery_power', 100)->nullable();
            $table->integer('num_batteries')->nullable();

            $table->string('front_tyre_size', 100)->nullable();;
            $table->string('number_of_tyres', 100)->nullable();;
            $table->string('rear_tyre_size', 100)->nullable();;
            $table->string('tyre_brand', 100)->nullable();
            $table->string('vehicle_header_id')->nullable();
            $table->integer('created_by')->nullable();
            $table->string('created_name')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('VM_ENGINE_DETAILS');
    }
};

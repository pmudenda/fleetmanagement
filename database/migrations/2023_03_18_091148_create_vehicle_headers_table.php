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
        Schema::create('VM_VEHICLE_HEADER', function (Blueprint $table) {
            $table->id();
            $table->string('brand_code')->nullable();
            $table->string('brand_name');
            $table->string('model_name');
            $table->string('model_code');
            $table->string('body_type_code');
            $table->string('body_type_name');
            $table->string('barcode', 255)->nullable();
            $table->string('has_tom_card')->default('N');
            $table->string('on_boarding_status', 4)->nullable();
            $table->string('status', 4)->nullable();
            $table->decimal('mileage', 19, 4)->nullable();

            $table->string('registration_number', 10);
            $table->string('business_unit_code');
            $table->string('business_unit_name');
            $table->integer('invalid_odometer_entry');
            $table->string('location_code', 200);
            $table->string('location_name')->nullable();
            $table->string('registration_type', 4);

            $table->string('created_by');
            $table->string('created_name');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('VM_VEHICLE_HEADER');
    }
};

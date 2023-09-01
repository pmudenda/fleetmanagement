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
        Schema::create('VM_VEHICLE_HEADER', function (Blueprint $table){
            $table->id();
            $table->string('registration_number', 10);
            $table->string('brand_code',2)->nullable();
            $table->string('brand_name')->nullable();
            $table->string('model_name')->nullable();
            $table->string('model_code', 2);
            $table->string('body_type_code', 2);
            $table->string('body_type_name')->nullable();
            $table->string('barcode', 255)->nullable();
            $table->string('has_tom_card', 1)->default('N');

            $table->string('status', 4)->nullable();
            $table->decimal('mileage', 19, 4)->nullable();
            $table->string('registration_type', 4);

            $table->string('business_unit_code');
            $table->string('business_unit_name');

            $table->string('location_code', 200);
            $table->string('location_name', 100)->nullable();
            $table->string('type_brand_model', 6)->nullable();
            $table->integer('invalid_odometer_entry');
            $table->string('on_boarding_status', 4)->nullable();
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

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
        Schema::create('VM_VEHICLE_HEADER', function (Blueprint $table) {
            $table->id();
            //$table->string('brand_guid');
            $table->string('brand_guid')->nullable();
            $table->string('brand_name');
            $table->string('model_guid');
            $table->string('model_name');
            $table->string('model_code');
            $table->string('body_type_guid');
            $table->string('body_type_name');
            $table->string('registration_number');
            $table->string('business_unit_code');
            $table->string('business_unit_name');
            $table->string('location_code');
            $table->string('location_name');
            $table->string('created_by');
            $table->string('created_name');
            $table->timestamp('deleted_at')->nullable();
            $table->string('barcode')->nullable();
            $table->string('on_boarding_status')->nullable();
            $table->string('status')->nullable();

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

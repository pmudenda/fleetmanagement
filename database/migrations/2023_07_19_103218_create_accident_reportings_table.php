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
        Schema::create('VM_ACCIDENT', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 20);
            $table->string('area', 50);
            $table->string('vehicle_reg_no', 10);
            $table->string('driver', 8);
            $table->timestamp('date_of_accident');
            $table->timestamp('time_of_accident');
            $table->timestamp('date_reported');
            $table->timestamp('time_reported');
            $table->string('nature_of_accident', 10);
            $table->string('type_of_accident', 10);
            $table->string('guilty', 3);
            $table->string('location');
            $table->string('death');
            $table->string('reported_by', 10);
            $table->integer('num_passengers');
            $table->double('mileage', 20, 2);
            $table->string('other_people_involved', 3);
            $table->string('day_of_week');
            $table->string('other_vehicle_involved');
            $table->string('property', 255);
            $table->string('vehicle_insured', 3);
            $table->integer('driver_experience');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('VM_ACCIDENT');
    }
};

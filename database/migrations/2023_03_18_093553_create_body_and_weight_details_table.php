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
        Schema::create('VM_BODY_AND_WEIGHT_DETAILS', function (Blueprint $table) {
            $table->id();
            $table->string('distanceAxle1');
            $table->string('distanceAxle2');
            $table->string('distanceAxle3');
            $table->string('distanceAxle4');
            $table->string('height');
            $table->string('length');
            $table->string('numberOfSeats');
            $table->string('seatCapFront');
            $table->string('seatCapRear');
            $table->string('volumeOfBootTanker');
            $table->string('width');

            $table->string('grossWeight');
            $table->string('tareWeight');
            $table->string('trailerWeight2');
            $table->string('trailerWeight3');
            $table->string('trailerWeight4');

            $table->string('vehicle_header_id');
            $table->string('created_by', 100)->nullable();
            $table->string('created_name', 100)->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('VM_BODY_AND_WEIGHT_DETAILS');
    }
};

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

            $table->decimal('height');
            $table->decimal('length');
            $table->decimal('width', 11, 2);
            $table->integer('numberOfSeats');
            $table->decimal('grossWeight', 11, 4);
            $table->decimal('tareWeight', 11, 4);

            $table->string('seatCapFront', 2)->nullable();
            $table->string('seatCapRear', 2)->nullable();
            $table->string('volumeOfBootTanker')->nullable();

            $table->string('reg_no', 10)->nullable();
            $table->string('distanceAxle1')->nullable();
            $table->string('distanceAxle2')->nullable();
            $table->string('distanceAxle3')->nullable();
            $table->string('distanceAxle4')->nullable();
            $table->string('trailerWeight2')->nullable();
            $table->string('trailerWeight3')->nullable();
            $table->string('trailerWeight4')->nullable();

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

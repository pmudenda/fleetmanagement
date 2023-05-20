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
        Schema::create('CONFIG_CHARGE_OUT_RATE', function (Blueprint $table) {
            $table->id();
            $table->string('created_by', 255);
            $table->string('modified_by', 255)->nullable();
            $table->string('vehicle_specification', 20);
            $table->string('vehicle_description');
            $table->decimal('charge', 18, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('CONFIG_CHARGE_OUT_RATE');
    }
};

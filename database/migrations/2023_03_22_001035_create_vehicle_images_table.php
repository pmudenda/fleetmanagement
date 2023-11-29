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
        Schema::create('VM_VEHICLE_IMAGES', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_header_id', 100);
            $table->string('file_name', 200);
            $table->string('file_path', 255);
            $table->string('view', 255);
            $table->integer('created_by');
            $table->string('created_name', 200);
            $table->datetime('period_start');
            $table->datetime('period_end');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('VM_VEHICLE_IMAGES');
    }
};

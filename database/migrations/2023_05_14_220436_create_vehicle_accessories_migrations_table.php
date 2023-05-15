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
        Schema::create('VM_ACCESSORIES_TABLE', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_header_id');
            $table->string('vehicle_header_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('VM_ACCESSORIES_TABLE');
    }
};

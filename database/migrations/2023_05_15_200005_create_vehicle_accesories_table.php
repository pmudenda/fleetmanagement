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
        Schema::create('VM_VEHICLE_ACCESSORIES', function (Blueprint $table) {
            $table->id();
            $table->string('created_by', 255)->nullable();
            $table->string('name', 255);
            $table->string('code', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('VM_VEHICLE_ACCESSORIES');
    }
};

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
        Schema::create('CONFIG_VEHICLE_BRANDS', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('status')->nullable();
            $table->string('created_by', 10)->nullable();
            $table->string('modified_by',10)->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('CONFIG_VEHICLE_BRANDS');
    }
};

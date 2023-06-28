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
        Schema::create('CONFIG_VEHICLE_MODELS', function (Blueprint $table) {
            $table->id();
            $table->uuid('brand_guid')->nullable();
            $table->string('brand_name') ;
            $table->string('model_guid') ;
            $table->string('model_name') ;
            $table->string('model_code') ;
            $table->string('status') ;
            $table->datetime('date_created')->default(Carbon\Carbon::now());
            $table->timestamp('deleted_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('created_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('CONFIG_VEHICLE_MODELS');
    }
};

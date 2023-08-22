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
        Schema::create('CONFIG_VEHICLE_MODELS', function (Blueprint $table) {
            $table->id();
            $table->string('code', 4);
            $table->string('model_name', 255);
            $table->string('model_code', 4)->nullable();
            $table->string('brand_code', 4)->nullable();
            $table->string('body_type_code', 4);
            $table->string('status')->nullable();
            $table->string('created_by', 20)->nullable();
            $table->string('modified_by', 20)->nullable();
            $table->timestamp('deleted_at')->nullable();
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

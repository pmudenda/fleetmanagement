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
        Schema::create('wm_imprest_buy_details', function (Blueprint $table) {
            $table->id();
            $table->string('header_reference', 20)->nullable();
            $table->string('vehicle_registration', 10)->nullable();
            $table->string('material_code', 20)->nullable();
            $table->string('description', 255)->nullable();
            $table->string('specification', 255)->nullable();
            $table->integer('quantity')->nullable();
            $table->string('unit_of_measure',10)->nullable();
            $table->decimal('unit_price', 19,4)->nullable();
            $table->decimal('total_price', 19,4)->nullable();
            $table->string('created_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wm_imprest_buy_details');
    }
};

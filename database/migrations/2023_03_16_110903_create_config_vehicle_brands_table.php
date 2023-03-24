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
            $table->string('guid');
            $table->string('name');
            $table->string('status');
            $table->datetime('date_created')->default(Carbon\Carbon::now());
            $table->timestamp('deleted_at')->nullable();
            $table->integer('created_by')->nullable();
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

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
        Schema::create('WM_JOB_CARD_VEHICLE_ACCESSORIES', function (Blueprint $table) {
            $table->id();
            $table->string('job_card_no', 15);
            $table->string('name', 255);
            $table->string('code', 10);
            $table->string('is_present', 10);
            $table->string('remarks', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_shop_vehicle_accessories');
    }
};

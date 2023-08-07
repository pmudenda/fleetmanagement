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
        Schema::create('wm_mechanics', function (Blueprint $table) {
            $table->id();
            $table->string('staff_no', 10);
            $table->string('name', 255);
            $table->string('workshop_code', 4)->nullable();
            $table->string('section_code', 4)->nullable();
            $table->string('status', 2);
            $table->string('created_by', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wm_mechanics');
    }
};

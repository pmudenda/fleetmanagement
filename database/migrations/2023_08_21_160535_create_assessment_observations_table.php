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
        Schema::create('wm_assessment_observations', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 20);
            $table->string('image_path', 255)->nullable();
            $table->string('remarks', 255)->nullable();
            $table->string('reported_by', 10);
            $table->string('modified_by', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wm_assessment_observations');
    }
};

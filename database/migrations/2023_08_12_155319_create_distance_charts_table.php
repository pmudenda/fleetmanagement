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
        Schema::create('CONFIG_DISTANCES_CHART', function (Blueprint $table) {
            $table->id();
            $table->string('town_from', 255);
            $table->string('town_to', 255);
            $table->decimal('distance', 19,4);
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
        Schema::dropIfExists('CONFIG_DISTANCES_CHART');
    }
};

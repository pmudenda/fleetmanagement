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
        Schema::create('gen_simulations', function (Blueprint $table) {
            $table->id();
            $table->string("created_by")->nullable();
            $table->string("simulator", 20);
            $table->string("simulated", 20);
            $table->timestamp("simulate_start");
            $table->timestamp("simulate_end")->nullable();
            $table->string("comments", 1000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gen_simulations');
    }
};

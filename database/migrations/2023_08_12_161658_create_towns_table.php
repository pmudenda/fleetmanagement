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
        Schema::create('config_towns', function (Blueprint $table) {
            $table->id();
            $table->string('town_name', 255);
            $table->string('town_code', 20);
            $table->string('created_by', 20);
            $table->string('modified_by', 20);
            $table->timestamp('deleted_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config_towns');
    }
};

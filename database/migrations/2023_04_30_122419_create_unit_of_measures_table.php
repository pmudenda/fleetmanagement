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
        Schema::create('CONFIG_UNIT_OF_MEASURES', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('short_name', 5);
            $table->string('code', 4);
            $table->string('status', 4)->default('01');
            $table->string('created_by', 255)->nullable();
            $table->string('created_name', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('CONFIG_UNIT_OF_MEASURES');
    }
};

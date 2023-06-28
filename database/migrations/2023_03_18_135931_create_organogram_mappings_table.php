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
        Schema::create('organogram_mappings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('level_1',255)->nullable();
            $table->string('bu_description', 255)->nullable();
            $table->string('bu_code', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organogram_mappings');
    }
};

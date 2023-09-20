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
        Schema::create('GEN_REF_NUMBERS', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 20);
            $table->string('created_by', 20);
            $table->string('module', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('GEN_REF_NUMBERS');
    }
};

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
        Schema::create('DIRECTORATES', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('active');
            $table->string('code')->nullable();
            $table->string('code_unit', 10)->nullable();
            $table->string('active', 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('DIRECTORATES');
    }
};

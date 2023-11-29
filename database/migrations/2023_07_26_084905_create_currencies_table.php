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
        Schema::create('config_currencies', function (Blueprint $table) {
            $table->id();
            $table->string('created_by', 40)->nullable();
            $table->string('currency_code', 3);
            $table->string('abbreviation', 10)->nullable();
            $table->string('description', 50)->nullable();
            $table->string('country_code', 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config_currencies');
    }
};

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
        Schema::create('GEN_ARTICLES', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('description', 255)->nullable();
            $table->string('code', 10);
            $table->string('status', 4);
            $table->decimal('price', 4);
            $table->string('unit_of_measure_code', 4);
            $table->string('group_code', 4);
            $table->string('code_article')->nullable();
            $table->string('created_by')->nullable();
            $table->string('created_by_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('GEN_ARTICLES');
    }
};

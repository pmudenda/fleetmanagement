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
        Schema::create('CONFIG_GENERAL_TABLES', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 10)->nullable();
            $table->string('description')->nullable();
            $table->string('type', 100);
            $table->string('active', 2)->nullable();
            $table->string('module',10)->nullable();
            $table->string('created_by', 255)->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('CONFIG_GENERAL_TABLES');
    }
};

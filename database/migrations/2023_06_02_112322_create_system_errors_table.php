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
        Schema::create('CONFIG_SYS_ERROR_MESSAGES', function (Blueprint $table) {
            $table->id();
            $table->string('error_code', 10);
            $table->string('error_message', 2000);
            $table->string('error_type', 2000)->nullable();
            $table->string('created_by', 10)->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('CONFIG_SYS_ERROR_MESSAGES');
    }
};

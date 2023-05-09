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
        Schema::create('WFL_WORKFLOW_PROCESSES', function (Blueprint $table) {
            $table->id();
            $table->string('process_code');
            $table->string('name');
            $table->string('description');
            $table->Integer('created_by');
            $table->Integer('modified_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_process');
    }
};

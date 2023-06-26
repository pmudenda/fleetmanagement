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
        Schema::create('WFL_WORKFLOW_STEP', function (Blueprint $table) {
            $table->id();
            $table->string('process_id');
            $table->string('step_id');
            $table->string('name');
            $table->boolean('is_initial_step');
            $table->boolean('is_final_step');
            $table->string('previous_step');
            $table->string('next_step');
            $table->string('next_process');
            $table->string('action_page');
            $table->Integer('created_by',);
            $table->Integer('modified_by');
            $table->string('privilege');
            $table->Integer('modified_by')->nullable();
            $table->string('previous_step')->nullable();
            $table->string('next_step')->nullable();
            $table->string('next_process')->nullable();
            $table->string('action_page')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_step');
    }
};

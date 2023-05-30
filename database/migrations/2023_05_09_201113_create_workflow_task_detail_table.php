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
        Schema::create('WFL_WORKFLOW_TASK_DETAILS', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->string('process_code');
            $table->string('user_id');
            $table->string('current_step_id');
            $table->string('actioning_officer');
            $table->string('status');
            $table->string('date_started');
            $table->string('date_ended');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('WFL_WORKFLOW_TASK_DETAILS');
    }
};

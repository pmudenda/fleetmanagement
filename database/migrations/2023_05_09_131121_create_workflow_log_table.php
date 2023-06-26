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
        Schema::create('WFL_WORKFLOW_LOGS', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 255);
            $table->string('step_id', 255);
            $table->string('actioning_officer', 255);
            $table->string('action', 255);
            $table->string('activity', 255);
            $table->string('status', 255);
            $table->string('action_date', 255);
            $table->string('next_step', 255);
            $table->string('previous_step', 255);
            $table->string('remarks', 255);
            $table->timestamps();

           /* $table->id();
            $table->string('reference', 25);
            $table->string('step_id', 04);
            $table->string('action', 4);
            $table->string('activity', 255);
            $table->string('actioning_officer', 100);
            $table->string('status', 50);
            $table->string('next_step', 15)->nullable();
            $table->date('action_date');
            $table->string('previous_step', 5)->nullable();
            $table->string('remarks', 255);
            $table->timestamps();*/
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_log');
    }
};

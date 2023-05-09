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
        Schema::create('WFL_WORK_FLOW_LOGS', function (Blueprint $table) {
            $table->id();
            $table->string('Reference', 255);
            $table->string('StepId', 255);
            $table->string('ActioningOfficer', 255);
            $table->string('Action', 255);
            $table->string('Status', 255);
            $table->string('ActionDate', 255);
            $table->string('NextStep', 255);
            $table->string('PreviousStep', 255);
            $table->string('Remarks', 255);
            $table->timestamps();
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

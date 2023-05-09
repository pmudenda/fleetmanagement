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
            $table->string('Reference', 255);
            $table->string('Step_Id', 255);
            $table->string('Actioning_Officer', 255);
            $table->string('Action', 255);
            $table->string('Status', 255);
            $table->string('Action_Date', 255);
            $table->string('Next_Step', 255);
            $table->string('Previous_Step', 255);
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

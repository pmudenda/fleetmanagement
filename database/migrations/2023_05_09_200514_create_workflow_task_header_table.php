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
        Schema::create('WFL_WORKFLOW_TASK', function (Blueprint $table) {
            $table->id();
            $table->string('message');
            $table->string('status');
            $table->string('date_acted');
            $table->string('subject');
            $table->string('assigned_user');
            $table->string('sender');
            $table->string('url');
            $table->string('reference');
            $table->string('priority');
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
        Schema::dropIfExists('workflow_task');
    }
};

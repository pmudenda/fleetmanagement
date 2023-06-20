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
            $table->string('status');
            $table->string('date_acted');
            $table->string('subject', 255);
            $table->string('assigned_user');
            $table->string('long_description', 500);
            $table->string('url');
            $table->string('reference');
            $table->string('priority');
            $table->string('description', 255);
            $table->string('user_unit', 10);
            $table->Integer('created_by');
            $table->Integer('modified_by');
            $table->decimal('amount', 18, 2);
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

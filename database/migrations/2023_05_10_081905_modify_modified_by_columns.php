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
        Schema::table('WFL_WORKFLOW_TASK_DETAILS', function (Blueprint $table) {
            $table->Integer('created_by')->nullable()->add();
            $table->Integer('modified_by')->nullable()->add();
        });

        Schema::table('WFL_WORKFLOW_PROCESSES', function (Blueprint $table) {
            //$table->dropColumn('process_code');
            $table->Integer('modified_by')->nullable()->change();
        });

        Schema::table('WFL_WORKFLOW_PROCESSES', function (Blueprint $table) {
            $table->string('process_code')->unique()->change();
        });

        Schema::table('WFL_WORKFLOW_STEP', function (Blueprint $table) {
            $table->Integer('modified_by')->nullable()->change();

            $table->string('previous_step')->nullable()->change();
            $table->string('next_step')->nullable()->change();
            $table->string('next_process')->nullable()->change();
            $table->string('action_page')->nullable()->change();
        });

        Schema::table('WFL_WORKFLOW_TASK', function (Blueprint $table) {
            $table->Integer('modified_by')->nullable()->change();
            $table->dropColumn('date_acted');
            $table->timestamp('date_ended')->nullable()->add();
        });

        Schema::table('WFL_WORKFLOW_TASK', function (Blueprint $table) {
            $table->timestamp('date_acted')->nullable()->add();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

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
        Schema::create('WFL_WORKFLOW_APPROVAL_LIMIT', function (Blueprint $table) {
            $table->id();
            $table->string('user_unit_code', 10);
            $table->string('user_unit_name', 10);
            $table->string('office', 255);
            $table->string('final_step', 4);
            $table->decimal('approval_limit', 18,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('WFL_WORKFLOW_FUNCTIONAL_OFFICE');
    }
};

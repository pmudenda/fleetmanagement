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
        Schema::create('vm_fuel_allocations', function (Blueprint $table) {
            $table->id();
            $table->string('created_by', 12)->nullable();
            $table->string('modified_by', 12)->nullable();
            $table->integer('allocation_amount');
            $table->date('period_from');
            $table->date('period_to');
            $table->string('status', 2);
            $table->string('reg_no', 10);
            $table->string('user_update', 12);
            $table->string('valid_for', 4);
            $table->integer('balance');
            $table->string('justification', 255);
            $table->timestamp('deleted_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vm_fuel_allocations');
    }
};

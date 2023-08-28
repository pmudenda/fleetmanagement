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
        Schema::create('vm_tom_card_allocations', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no', 10);
            $table->date('period_from');
            $table->date('period_to');
            $table->string('status', 2);
            $table->string('created_by', 12)->nullable();
            $table->string('modified_by', 12)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vm_tom_card_allocations');
    }
};

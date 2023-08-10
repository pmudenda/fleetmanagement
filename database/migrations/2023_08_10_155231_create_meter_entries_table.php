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
        Schema::create('vm_meter_entry_header', function (Blueprint $table) {
            $table->id();
            $table->string('source');
            $table->string('auto_void_reason');
            $table->string('logged_by', 10);
            $table->decimal('meter_start', 19, 4)->nullable();
            $table->decimal('meter_end', 19, 4);
            $table->decimal('meter_done', 19, 4);
            $table->string('reg_no', 10);
            $table->decimal('driver', 10);
            $table->decimal('driver_name', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vm_meter_entry_header');
    }
};

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
        Schema::create('CONFIG_WORKSHOP', function (Blueprint $table) {
            $table->id();
            $table->string('workshop_code', 15);
            $table->string('workshop_name', 255);
            $table->string('area_code', 2);
            $table->string('status', 2);
            $table->string('cost_center', 10);
            $table->string('user_unit', 10)->nullable();
            $table->string('business_unit', 10)->nullable();
            $table->string('created_by', 10)->nullable();
            $table->string('modified_by', 10)->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_shops');
    }
};

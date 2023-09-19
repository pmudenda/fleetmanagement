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
        Schema::create('gen_vehicle_status_history', function (Blueprint $table) {
            $table->id();
            $table->string('created_by',10);
            $table->string('updated_by',10)->nullable();
            $table->string('code',6)->nullable();
            $table->string('reference',20)->nullable();
            $table->string('page', 2)->default('1');
            $table->string('description', 200)->nullable();
            $table->string('reg_no',10);
            $table->string('status', 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gen_vehicle_status_history');
    }
};

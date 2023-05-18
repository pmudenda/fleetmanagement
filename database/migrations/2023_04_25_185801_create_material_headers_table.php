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
        Schema::create('GEN_MATERIAL_HEADERS', function (Blueprint $table) {
            $table->id();
            $table->string('proc_ref');
            $table->string('req_no');
            $table->string('veh_reg_no')->nullable();
            $table->timestamp('valid_date_from')->nullable();
            $table->timestamp('valid_date_to')->nullable();
            $table->date('date_created');
            $table->string('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('GEN_MATERIAL_HEADERS');
    }
};

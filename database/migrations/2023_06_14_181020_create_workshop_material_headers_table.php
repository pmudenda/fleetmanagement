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
        Schema::create('WM_WORKSHOP_MATERIALS_HEADER', function (Blueprint $table) {
            $table->id();
            $table->string('item_type_code', 2)->nullable();
            $table->string('workshop_reference', 20)->nullable();
            $table->string('workshop_code', 20)->nullable();
            $table->timestamp('request_date')->nullable();
            $table->timestamp('collection_date')->nullable();
            $table->string('supplier_code', 10)->nullable();
            $table->string('purchasing_office', 10)->nullable();
            $table->string('job_card_no' ,20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('WM_WORKSHOP_MATERIALS_HEADER');
    }
};

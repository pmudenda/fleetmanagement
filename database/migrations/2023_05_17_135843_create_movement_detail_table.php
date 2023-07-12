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
        Schema::create('SM_MOVEMENT_DETAILS', function (Blueprint $table) {
            $table->id();
            $table->string('created_by', 20)->nullable();
            $table->timestamp('created_date');
            $table->string('document_number',20);
            $table->string('material_code', 30);
            $table->Integer('quantity');
            $table->decimal('price', 18, 2);
            $table->string('description', 255);
            $table->string('specification', 255)->nullable();
            $table->string('unit_of_measure', 20);
            $table->string('article_type', 255);
            $table->string('transaction_type', 255);
            $table->string('stf_number', 255)->nullable();
            $table->string('veh_reg_no', 10)->nullable();
            $table->string('authorised_by', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('SM_MOVEMENT_DETAILS');
    }
};

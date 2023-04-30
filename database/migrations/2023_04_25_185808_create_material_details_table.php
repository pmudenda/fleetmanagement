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
        Schema::create('GEN_MATERIAL_DETAILS', function (Blueprint $table) {
            $table->id();
            $table->string('req_no');
            $table->string('material_code');
            $table->Integer('quantity');
            $table->string('unit_of_measure');
            $table->string('specifications');
            $table->string('project_code')->nullable();
            $table->string('supplier_code')->nullable();

            $table->string('reg_no')->nullable();
            $table->Integer('amount');
            $table->decimal('price');
            $table->string('ref_no')->nullable();
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
        Schema::dropIfExists('GEN_MATERIAL_DETAILS');
    }
};

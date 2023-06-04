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
            $table->decimal('amount', 18, 2);
            $table->decimal('price', 18, 2);
            $table->string('ref_no')->nullable();
            $table->date('date_created');
            $table->string('created_by');
            $table->string('cost_centre', 15)->nullable();
            $table->string('stores_code', 15)->nullable();
            $table->string('movt_no', 15)->nullable();
            //$table->string('cost_centre_name', 15)->nullable()->add();
            //$table->string('project_name', 15)->nullable()->add();
            $table->string('cost_centre_name', 255)->nullable();
            $table->string('project_name', 255)->nullable();
            $table->string('max_allowed', 15)->nullable();
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

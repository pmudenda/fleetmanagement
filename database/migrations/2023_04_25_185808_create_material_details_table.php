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
            $table->string('req_no', 10);
            $table->string('material_code', 20);
            $table->Integer('quantity');
            $table->string('unit_of_measure', 15);
            $table->string('specifications', 2000);
            $table->string('project_code', 20)->nullable();
            $table->string('supplier_code', 20)->nullable();
            $table->string('reg_no', 10)->nullable();
            $table->decimal('amount', 18, 2);
            $table->decimal('price', 18, 2);
            $table->string('ref_no')->nullable();
            $table->date('date_created');
            $table->string('created_by', 10);
            $table->string('cost_centre', 15)->nullable();
            $table->string('stores_code', 15)->nullable();
            $table->string('movt_no', 15)->nullable();
            //$table->string('cost_centre_name', 15)->nullable()->add();
            //$table->string('project_name', 15)->nullable()->add();
            $table->string('cost_centre_name', 255)->nullable();
            $table->string('project_name', 255)->nullable();
            $table->string('max_allowed', 15)->nullable();
            $table->Integer('quantity_issued')->default(0)->nullable();
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

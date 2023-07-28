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
        Schema::create('VM_FUEL_ISSUE', function (Blueprint $table) {
            $table->id();
            $table->string('created_by', 20);
            $table->string('voucher_no', 20);
            $table->timestamp('voucher_date');
            $table->timestamp('voucher_time');
            $table->string('document_no', 20)->nullable();
            $table->string('reg_no', 10);
            $table->string('cost_center', 12);
            $table->string('area_code', 2);
            $table->string('authorised_by', 50);
            $table->string('received_by', 50);
            $table->string('issue_office', 50);
            $table->string('unit_of_measure', 6);
            $table->string('fuel_code', 12);
            $table->decimal('quantity', 19, 4)->nullable();
            $table->decimal('price', 19, 4)->nullable();
            $table->decimal('amount', 19, 4)->nullable();
            $table->integer('odometer')->nullable();
            $table->decimal('pump_start')->nullable();
            $table->decimal('pump_end')->nullable();
            $table->string('status', 15);
            $table->string('business_unit', 8)->nullable();
            $table->string('user_unit', 15)->nullable();
            $table->string('system_origin', 10)->nullable();
            $table->string('requisition_type', 2)->nullable();
            $table->string('justification', 2000)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('VM_FUEL_ISSUE');
    }
};

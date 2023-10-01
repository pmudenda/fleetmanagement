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
        Schema::create('vm_insurance', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no', 10);
            $table->string('policy_no', 100);
            $table->date('period_from');
            $table->date('period_to');
            $table->decimal('insured_amount', 19, 2);
            $table->decimal('premium', 19, 2);
            $table->date('payment_date');
            $table->string('certificate_number', 190);
            $table->string('insurance_sub_type', 10);
            $table->string('created_by', 100)->nullable();
            $table->string('modified_by', 100)->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vm_insurance');
    }
};

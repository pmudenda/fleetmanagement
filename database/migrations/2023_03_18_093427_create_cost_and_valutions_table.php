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
        Schema::create('VM_COST_AND_VALUATIONS', function (Blueprint $table) {
            $table->id();
            $table->string('assetNumber');
            $table->decimal('bookValue', 19, 2);
            $table->decimal('costOfLicense', 19,2);
            $table->decimal('costPrice', 19, 2);
            $table->decimal('premium', 19, 2);
            $table->string('supplierName', 255);
            $table->integer('yearOfPurchase');
            $table->timestamp('deleted_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->string('created_name')->nullable();
            $table->string('reg_no', 10)->nullable();
            $table->string('vehicle_header_id', 191);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('VM_COST_AND_VALUATIONS');
    }
};

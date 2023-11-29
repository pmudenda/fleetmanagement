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
        Schema::create('vm_road_tax', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no', 10);
            $table->string('licence_no', 100);
            $table->date('valid_from');
            $table->date('valid_to');
            $table->decimal('cost', 19, 2);
            $table->date('payment_date');
            $table->string('order_no')->nullable();
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
        Schema::dropIfExists('vm_road_tax');
    }
};

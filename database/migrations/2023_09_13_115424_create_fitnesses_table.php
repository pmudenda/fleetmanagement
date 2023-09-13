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
        Schema::create('vm_fitness', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no', 10);
            $table->string('book_number', 100);
            $table->date('period_from');
            $table->date('period_to');
            $table->decimal('amount', 19, 2);
            $table->date('payment_date')->nullable();
            $table->string('created_by', 100)->nullable();
            $table->string('comments', 2000)->nullable();
            $table->string('result', 2)->nullable();
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
        Schema::dropIfExists('vm_fitness');
    }
};

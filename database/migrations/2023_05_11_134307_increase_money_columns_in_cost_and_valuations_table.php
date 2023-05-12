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
        Schema::table('VM_COST_AND_VALUATIONS', function (Blueprint $table) {
            $table->decimal('bookValue', 18, 2)->change();
            $table->decimal('costOfLicense',18, 2)->change();
            $table->decimal('costPrice',18, 2)->change();
            $table->decimal('premium',18, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('VM_COST_AND_VALUATIONS', function (Blueprint $table) {
            //
        });
    }
};

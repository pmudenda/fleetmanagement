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
       /* Schema::create('GEN_GENERAL_TABLES', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('code', 255);
            $table->string('status', 255);
            $table->string('created_by')->nullable();
            $table->string('created_by_name')->nullable();
            $table->timestamps();
        });*/
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('general_tables');
    }
};

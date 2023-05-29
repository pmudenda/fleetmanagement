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
        /*Schema::table('DM_DRIVER', function (Blueprint $table) {
            $table->string('license_front', 255)->nullable();
            $table->string('license_back', 255)->nullable();
            $table->string('permit', 255)->nullable();
        });*/
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('DM_DRIVER', function (Blueprint $table) {
            //
        });
    }
};

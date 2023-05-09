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
        Schema::table('CONFIG_VEHICLE_BRANDS', function (Blueprint $table) {
            $table->uuid('guid')->change();
        });

        Schema::table('CONFIG_VEHICLE_MODELS', function (Blueprint $table) {
            $table->uuid('brand_guid')->change();
        });

        Schema::table('CONFIG_VEHICLE_BODY_TYPES', function (Blueprint $table) {
            $table->uuid('guid')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('db_generated_uuid', function (Blueprint $table) {
            //
        });
    }
};

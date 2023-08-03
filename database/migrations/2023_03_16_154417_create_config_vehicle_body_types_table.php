<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('CONFIG_VEHICLE_BODY_TYPES', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('status', 50);
            $table->string('code')->nullable();
            $table->string('guid')->nullable();
            $table->dateTime('date_created')->nullable();
            $table->string('body_type_name', 100);
            $table->timestamp('deleted_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('CONFIG_VEHICLE_BODY_TYPES');
    }
};

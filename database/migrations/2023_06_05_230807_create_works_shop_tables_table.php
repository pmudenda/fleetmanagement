<?php

use App\Helpers\StatusHelper;
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
        Schema::create('CONFIG_VEHICLE_DEFECTS', function (Blueprint $table) {
            $table->id();
            $table->string('type_code', 10)->nullable();
            $table->string('parent', 10)->nullable();
            $table->string('code', 4)->nullable();
            $table->string('status', 4)->default(StatusHelper::active())->nullable();
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('CONFIG_VEHICLE_DEFECTS');
    }
};

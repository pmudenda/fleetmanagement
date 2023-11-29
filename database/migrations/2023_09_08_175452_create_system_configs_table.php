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
        Schema::create('gen_system_configs', function (Blueprint $table) {
            $table->id();
            $table->string('config_file_name', 50);
            $table->string('name', 50);
            $table->string('value', 150);
            $table->string('status', 1);
            $table->string('data_type', 150)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gen_system_configs');
    }
};

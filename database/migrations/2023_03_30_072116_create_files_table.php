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
        Schema::create('GEN_FILES', function (Blueprint $table) {
            $table->id();
            $table->string('module');
            $table->string('reference_number');
            $table->string('name');
            $table->string('originalDocumentName');
            $table->string('extension');
            $table->string('path');
            $table->string('file_type');
            $table->string('file_size');

            $table->string('status')->default('01')->nullable();
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_to')->nullable();

            $table->string('created_by');
            $table->string('created_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('GEN_FILES');
    }
};

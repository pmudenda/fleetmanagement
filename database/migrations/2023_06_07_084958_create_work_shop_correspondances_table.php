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
        Schema::create('WM_WORK_SHOP_COMMENTS', function (Blueprint $table) {
            $table->id();
            $table->string('workshop_reference', 20);
            $table->string('type', 20);
            $table->string('remarks', 300);
            $table->string('status', 4);
            $table->string('created_by', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('WM_WORK_SHOP_COMMENTS');
    }
};

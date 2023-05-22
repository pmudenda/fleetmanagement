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
        Schema::create('DM_DRIVER', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('staff_number', 15);
            $table->string('grade', 4);
            $table->string('position', 100);
            $table->string('location', 255);
            $table->string('license_number', 15);
            $table->date('license_date_issued');
            $table->date('license_date_expiry');
            $table->string('license_category', 5);
            $table->string('permit_number', 25);
            $table->date('permit_date_issued');
            $table->date('permit_date_expiry');
            $table->date('id_designated');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('DM_DRIVER');
    }
};

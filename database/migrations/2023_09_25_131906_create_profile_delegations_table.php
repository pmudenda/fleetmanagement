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
        Schema::create('sec_profile_delegation', function (Blueprint $table) {
            $table->id();
            $table->string('profile_owner', 10);
            $table->string('delegated_to', 10);

            $table->string('owner_profile_id', 10);
            $table->string('delegated_profile_id', 10);

            $table->timestamp('period_from');
            $table->timestamp('period_to');
            $table->string('justification', 255);
            $table->string('cancellation_remarks', 255)->nullable();
            $table->string('created_by', 10);
            $table->string('modified_by', 10)->nullable();
            $table->string('cancelled_by', 10)->nullable();
            $table->date('date_cancelled')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sec_profile_delegation');
    }
};

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
        Schema::create('gate_passes', function (Blueprint $table) {

            $table->id();
            $table->string('reference_number', 20);
            $table->integer('type');
            $table->string('reg_no');
            $table->text('purpose');
            $table->timestamp('expires_at');
            $table->timestamp('departure_at');
            $table->string('departure_town');
            $table->string('destination_town');

            //
            $table->integer('authorised_by');
            $table->timestamp('authorised_at');

            $table->integer('checked_by');
            $table->timestamp('checked_at');

            $table->integer('user_id');
            $table->string('status');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gate_passes');
    }
};

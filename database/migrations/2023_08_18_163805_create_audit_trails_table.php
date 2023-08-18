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
        Schema::create('gen_audit_trail', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('event_date');
            $table->string('referenceNumber', 255)->nullable();
            $table->string('event', 255);
            $table->string('subject', 255);
            $table->integer('user_id');
            $table->string('name', 255);
            $table->string('justification', 255);
            $table->string('field_action', 255);
            $table->longText('new_value', 4000)->nullable();
            $table->longText('old_value', 4000)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gen_audit_trail');
    }
};

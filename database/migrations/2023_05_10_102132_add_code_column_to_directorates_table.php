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
        Schema::table('REF_DIRECTORATES', function (Blueprint $table) {
            $table->string('code')->unique()->add();
            $table->string('active')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('REF_DIRECTORATES', function (Blueprint $table) {
            //
        });
    }
};

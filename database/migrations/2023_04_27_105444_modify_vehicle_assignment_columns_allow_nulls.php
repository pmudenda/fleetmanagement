<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up(): void
    {
        Schema::table('VM_ASSIGNMENTS', function(Blueprint $table){
            $table->string('vehicleHolderName')->nullable()->add();
            $table->string('vehicleHolder')->nullable()->add();
            $table->string('businessUnit')->nullable()->add();
            $table->string('operatorStaffNumber')->nullable()->change();
            $table->string('operatorStaffNumber')->nullable()->change();
            $table->string('casualStaffName')->nullable()->change();
            $table->string('casualStaffNumber')->nullable()->change();
            $table->string('isTeamAssigned')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

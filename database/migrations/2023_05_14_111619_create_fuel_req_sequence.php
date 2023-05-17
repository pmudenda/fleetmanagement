<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //$sequence = DB::getSequence();
        // create a sequence
        //$sequence->create('REQ_FUEL_SEQUENCE');
        /*Schema::create('fuel_req_sequence', function (Blueprint $table) {
            $table->id();

            $table->timestamps();
        });*/
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_req_sequence');
    }
};

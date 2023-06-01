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
        Schema::create('WKS_JOB_CARD_HEADER', function (Blueprint $table) {
            $table->id();
            $table->string('workshop_doc_no', 255);
            $table->string('workshop_code', 7)->nullable();
            $table->string('veh_reg', 10)->nullable();
            $table->string('job_card_no', 15)->nullable();
            $table->string('driver_in', 7)->nullable();
            $table->string('repair_type', 7)->nullable();

            $table->date('date_in')->nullable();
            $table->time('time_in', 6)->nullable();
            $table->string('fuel_level_in', 10)->nullable();
            $table->integer('millage_in')->nullable();

            $table->string('receiving_section', 10)->nullable();
            $table->string('received_by', 10)->nullable();
            $table->date('expected_date_out')->nullable();
            $table->string('section_mid_code', 10)->nullable();

            $table->date('date_out')->nullable();
            $table->time('time_out', 6)->nullable();
            $table->string('fuel_level_out', 10)->nullable();
            $table->integer('millage_out')->nullable();
            $table->string('dispatching_section', 10)->nullable();
            $table->string('dispatched_by', 10)->nullable();

            $table->string('accident_ref', 15)->nullable();
            $table->string('book_ref', 15)->nullable();
            $table->string('driver_out', 7)->nullable();
            $table->decimal('repair_cost', 18, 2)->nullable();

            $table->string('odo_next_service', 15)->nullable();
            $table->string('service_due_after', 15)->nullable();
            $table->string('created_by', 15)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('WKS_JOB_CARD_HEADER');
    }
};

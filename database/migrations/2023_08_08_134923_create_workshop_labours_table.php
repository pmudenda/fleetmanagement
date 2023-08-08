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
        Schema::create('wm_workshop_labours', function (Blueprint $table) {
            $table->id();
            $table->string('wshp_act_code', 20);
            $table->string('wshp_code', 4);
            $table->string('section', 3);
            $table->string('evaluation')->nullable();
            $table->date('date_lab');
            $table->string('mechanic', 10);
            $table->decimal('hours_worked', 10, 2);
            $table->decimal('rate', 10, 2);
            $table->decimal('total_amount', 19, 2);
            $table->string('def_no');
            $table->string('created_by');
            $table->string('authorised_by')->nullable();
            $table->string('type_of_hour');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wm_workshop_labours');
    }
};

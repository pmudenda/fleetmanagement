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
        Schema::create('WM_VEHICLE_DEFECTS', function (Blueprint $table) {
            $table->id();
            $table->string('workshop_reference',20);
            $table->string('workshop_code',20);
            $table->string('veh_sys',20);
            $table->string('defect_category_code',20);
            $table->string('defect_name', 255)->nullable();
            $table->integer('defect_id')->nullable();
            $table->string('defect_code',20);
            $table->string('section_code',20);
            $table->timestamp('date_def')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('created_by',20);
            $table->string('modified_by',20)->nullable();

            /*
               SECTION_DEF            VARCHAR2(3)
               SECTION_CHK            VARCHAR2(3)
               DATE_CHK               DATE
               TIME_CHK               VARCHAR2(5)
               APPEARANCE             VARCHAR2(1)
               IDLER                  VARCHAR2(3)
             */

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('WM_VEHICLE_DEFECTS');
    }
};

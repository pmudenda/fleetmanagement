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
        Schema::create('wm_imprest_buy_headers', function (Blueprint $table) {
            $table->id();
            $table->string('cost_center')->nullable();
            $table->string('business_unit_code')->nullable();
            $table->string('user_unit_code')->nullable();
            $table->integer('user_unit_id')->nullable();
            $table->integer('pay_point_id')->nullable();
            $table->integer('projects_id')->nullable();
            $table->string('total_payment')->nullable();
            $table->string('change')->nullable();
            $table->string('code');
            $table->string('external_ref_no')->nullable();
            $table->string('status')->nullable();
            $table->string('name')->nullable();
            $table->string('staff_no')->nullable();
            $table->string('claim_date')->nullable();
            $table->string('authorised_by')->nullable();
            $table->string('date_authorised')->nullable();
            $table->integer('created_by');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wm_imprest_buy_headers');
    }
};

<?php

use App\Helpers\StatusHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('DM_DRIVER', function (Blueprint $table) {
            $table->string('created_by', 255);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('modified_by')->nullable();
            $table->string('status')->default(StatusHelper::active());
            $table->string('is_designated_driver', 2);
            $table->string('on_boarding_reference', 15);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('DM_DRIVER', function (Blueprint $table) {
            //
        });
    }
};

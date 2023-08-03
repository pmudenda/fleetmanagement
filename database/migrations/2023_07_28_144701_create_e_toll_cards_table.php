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
        Schema::create('CM_ETOLL_CARDS', function (Blueprint $table) {
            $table->id();
            $table->string('batchNumber', 255)->nullable();
            $table->string('cardScheme', 255);
            $table->string('cardNumber', 20);
            $table->string('cardStatus', 50);
            $table->timestamp('dateIssued');
            $table->timestamp('expiryDate' );
            $table->string('cvv', 3);
            $table->string('contactNumber', 15);
            $table->string('assignedTo', 255)->nullable();
            $table->string('responseHead', 255)->nullable();
            $table->string('responseHeadId', 8)->nullable();
            $table->string('comments', 255)->nullable();
            $table->string('created_by', 10)->nullable();
            $table->string('modified_by', 10)->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('CM_E_TOLL_CARDS');
    }
};

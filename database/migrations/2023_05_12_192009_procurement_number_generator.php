<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $command = "
        CREATE OR REPLACE FUNCTION procDocumentNumberGenerator(ls_type in VARCHAR2, ls_area IN VARCHAR2) RETURN STRING IS
        ls_return  VARCHAR2(255);
        BEGIN
             ls_return := (SEQUENCE_GENERATOR(ls_type,ls_area));
             RETURN ls_return;
        END;
      ";

        DB::connection()->getPdo()->exec($command);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        $command = "DROP FUNCTION procDocumentNumberGenerator";
        DB::connection()->getPdo()->exec($command);
    }
};

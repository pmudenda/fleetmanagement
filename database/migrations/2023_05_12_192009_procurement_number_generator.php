<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $command = "
        CREATE OR REPLACE FUNCTION procDocumentNumberGenerator(ls_type in VARCHAR2, ls_area IN VARCHAR2) RETURN STRING AS
        BEGIN
            RETURN '//TODO. replace with actual call';
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

<?php

namespace App\Services\Integration;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDO;

class ProcurementService
{
    public function generateDocumentNumber($requisitionType, $area): string
    {

        $requisitionType = empty($requisitionType) ? 'seq_store_req' : '';

        $result = DB::selectOne("select procDocumentNumberGenerator($requisitionType, $area) as value from dual");
        //DB::executeFunction('procDocumentNumberGenerator', ['ls_type' => 3, 'ls_area'], PDO::PARAM)
        Log::info('Document Number ', $result);
        return $result->value;
    }

}

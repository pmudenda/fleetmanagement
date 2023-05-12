<?php

namespace App\Services\Integration;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcurementService
{

    public function generateDocument($requisition, $area):string{
        $result = DB::selectOne("select procDocumentNumberGenerator('seq_store_req', 'NR') as value from dual");
        Log::info('Document Number ', $result);
        return $result->value;
    }

}

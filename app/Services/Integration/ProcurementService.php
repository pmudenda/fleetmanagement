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

        Log::info(' Generating Document For ' . $requisitionType . ' and Area ' . $area);
        //$result = DB::scalar("select storesDocumentNumberGenerator($requisitionType, $area) as value from dual");
        //$result =
        DB::executeFunction('storesDocumentNumberGenerator', ['ls_type' => $requisitionType, 'ls_area' => $area], PDO::PARAM_STR);
        //Log::info('Document Number ' . $result);
        return "";// $result->value;
    }

}

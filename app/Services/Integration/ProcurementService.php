<?php

namespace App\Services\Integration;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDO;

class ProcurementService
{
    public function generateDocumentNumber(string $requisitionType, string $area): string
    {
        Log::info(' Generating Document For ' . $requisitionType . ' and Area ' . $area);
        //$result = DB::scalar("select storesDocumentNumberGenerator($requisitionType, $area) as value from dual");
        $result = DB::executeFunction('storesDocumentNumberGenerator', ['ls_type' => trim($requisitionType), 'ls_area' => trim($area)], PDO::PARAM_STR);
        //Log::info('Document Number ' . $result);
        return "";// $result->value;
    }

}

<?php

namespace App\Services\Integration;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcurementService
{
    public function generateDocumentNumber(string $doc_type, string $area_code): string
    {
        try {
            Log::info(' Generating Document For ' . $doc_type . ' and Area ' . $area_code);
            //$result = DB::selectOne("select storesDocumentNumberGenerator($doc_type, $area_code) as value from dual");

            $pdo = DB::getPdo();
            $stmt = $pdo->prepare("begin :result := storesDocumentNumberGenerator(:ls_type, :ls_area); end;");
            $stmt->bindParam(':result', $result);
            $stmt->bindParam(':ls_type', $doc_type);
            $stmt->bindParam(':ls_area', $area_code);
            $stmt->execute();
            /*$result = DB::executeFunction(
                'storesDocumentNumberGenerator',
                ['ls_type' => trim($requisitionType), 'ls_area' => trim($area)],
                PDO::PARAM_STR);*/
            Log::info('Document Number ' . $result);
            return $result; //->value;
        } catch (\Exception $e) {
            Log::error($e);
            return "";
        }
    }

}

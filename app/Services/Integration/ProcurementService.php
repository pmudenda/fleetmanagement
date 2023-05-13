<?php

namespace App\Services\Integration;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcurementService
{
    public function generateDocumentNumber(string $doc_type, string $area_code): string
    {
        try {
            Log::info('Generating Document For ' . $doc_type . ' and Area ' . $area_code);
            $results = DB::select(
                'select storesDocumentNumberGenerator(:ls_type, :ls_area) as value from dual',
                ['ls_type' => $doc_type, 'ls_area' => $area_code]);

            $result = null;
            if (is_array($results) && !empty($results)) {
                $result = $results[0];
            }

            Log::info($result->value);

            $rawJNumber = $result->value;
            if (str_starts_with('0', $rawJNumber)) {
                return substr($rawJNumber, 1);
            }
            return $rawJNumber;
        } catch (\Exception $e) {
            Log::error($e);
            return "";
        }
    }

}

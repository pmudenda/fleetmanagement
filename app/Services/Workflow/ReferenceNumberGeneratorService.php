<?php

namespace App\Services\Workflow;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReferenceNumberGeneratorService
{
    public static function generateReferenceNumber($module): string
    {
        $results = DB::select("select fn_generate_reference_number (:p_module) as value from dual", ['p_module' => $module]);

        $result = null;
        if (is_array($results) && !empty($results)) {
            $result = $results[0];
        }

        Log::info($result->value);

        return $result->value;
    }
}

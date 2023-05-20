<?php

namespace App\Services\Workflow;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReferenceNumberGeneratorService
{
    public static function generateReferenceNumber($module): string
    {
        // use the total number of kilometer allowance in the system
        $results = DB::select("select fn_generate_reference_number (:p_module) as value from dual", ['p_module' => $module]);

        $result = null;
        if (is_array($results) && !empty($results)) {
            $result = $results[0];
        }

        Log::info($result->value);
        //random number
        /*$random = $count[0]->total;
        $random = sprintf("%07d", ($random + $value));
        $random = $prefix . $random;

        $count_existing_forms = DB::select("SELECT count(id) as total FROM " . trim($module) . " WHERE req_no = '{$random}'");
        try {
            $total = $count_existing_forms[0]->total;
        } catch (Exception $exception) {
            $total = 0;
        }

        if ($total < 1) {
            return $random;
        } else {
            $random = self::generateReferenceNumber($prefix, $value, $module);
        }*/

        return $result->value;
    }
}

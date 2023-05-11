<?php

namespace App\Services\Workflow;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReferenceNumberGeneratorService
{
    public static function generateReferenceNumber($prefix, $value, $module = 'GEN_MATERIAL_HEADERS'): string
    {
        // use the total number of kilometer allowance in the system
        $count = DB::select("SELECT count(id) as total FROM " . $module);
        //random number
        $random = $count[0]->total;
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
        }

        return $random;
    }
}

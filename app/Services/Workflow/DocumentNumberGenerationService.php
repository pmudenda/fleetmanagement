<?php

namespace App\Services\Workflow;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DocumentNumberGenerationService
{
    public static function generateReferenceNumber($module): string
    {
        $user_staff_no = auth()->user()->staff_no;
        $results = DB::select("select fn_generate_reference_number (:p_module, :p_user) as value from dual",
            ['p_module' => $module, 'p_user' => $user_staff_no]
        );

        $result = null;
        if (is_array($results) && !empty($results)) {
            $result = $results[0];
        }

        Log::info($result->value);

        return $result->value;
    }
}

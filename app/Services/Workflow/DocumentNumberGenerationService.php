<?php

namespace App\Services\Workflow;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDO;

class DocumentNumberGenerationService
{
    public static function generateReferenceNumber($module): string
    {
        $staffNumber = auth()->user()->staff_no;

        $pdo = DB::getPdo();
        $stmt = $pdo->prepare("begin :result := fn_generate_reference_number(:p_module, :p_user); end;");
        $stmt->bindParam(':result', $results, PDO::PARAM_STR, 255);
        $stmt->bindParam(':p_module', $module);
        $stmt->bindParam(':p_user', $staffNumber);
        $stmt->execute();

        $result = null;
        if (is_array($results) && !empty($results)) {
            $result = $results[0];
        }else{
            $result = $results;
        }

        Log::debug('Document Number '.$result);

        return $result;
    }
}

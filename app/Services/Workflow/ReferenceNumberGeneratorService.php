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

        /*$documentType = 'NCOF';
        $NumberTable = 'NC_NonConformanceReference';

        if (!Schema::hasTable($NumberTable)) {
            Schema::connection(env('DB_CONNECTION'))
                ->create($NumberTable, function ($table) {
                    $table->increments('id');
                    $table->string('businessUnit');
                    $table->timestamps();
                });
        }

        $result = DB::table(trim($NumberTable))
            //->where('businessUnit', '=', trim($businessUnitCode))
            ->where('directorate', '=', trim($directorateCode))
            ->select(DB::raw(" count(id) as documentCount "))
            ->first();

        $increment = 1;
        $documentNumberPrefix = $directorateCode . "." . $businessUnitCode . "." . $documentType . ".";

        $documentNumberSuffix = sprintf("%05d", ($result->documentCount + $increment));
        $referenceNumber = $documentNumberPrefix . $documentNumberSuffix;

        DB::table(trim($NumberTable))->insert([
            'businessUnit' => $businessUnitCode,
            'referenceNumber' => $referenceNumber,
            'directorate' => $directorateCode
        ]);

        return $referenceNumber;*/
    }
}

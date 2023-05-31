<?php

namespace App\Services\Integration;

use App\Constants\SystemOfOrigin;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDO;

class ProcurementSystemIntegrationService
{
    public function createStoresRequisition(
        $doc_no,
        $veh_reg_no,
        $stores_requisition_number,
        $account,
        $transactionType,
        $stores_code = '',
        $job_card_no = '',
        $delivery_site = ''
    ): string
    {
        try {
            Log::info('Generating Stores Requisition For Request ' . $doc_no . ' and Area ' . $stores_requisition_number);

            $ZESCOFleetMaster = SystemOfOrigin::ZESCOFleetMaster;
            /*$bindings = [
                'p_ref_no' => $doc_no,
                'p_reg_no' => $veh_reg_no,
                'p_store_code' => $stores_code,
                'p_user_requesting' => auth()->user()->staff_no,
                'p_job_card' => $job_card_no,
                'p_system_origin' => $ZESCOFleetMaster,
                'p_fleet_req_code' => $stores_requisition_number,
                'p_req_acc_number' => $account,
                'p_delivery_site' => $delivery_site,
                'p_transaction_type' => $transactionType,
                'p_current_user' => auth()->user()->staff_no,
            ];

            $results = DB::executeFunction('fn_create_stores_req', $bindings, PDO::PARAM_STR);
            */

            $pdo = DB::getPdo();

            $stmt = $pdo->prepare("begin :result := fn_create_stores_req(:p_ref_no, :p_reg_no, :p_store_code, :p_user_requesting, :p_job_card, :p_system_origin, :p_fleet_req_code, :p_req_acc_number, :p_delivery_site, :p_transaction_type, :p_current_user); end;");
            $stmt->bindParam(':result', $results);
            $stmt->bindParam(':p_ref_no', $doc_no);
            $stmt->bindParam(':p_reg_no', $veh_reg_no);
            $stmt->bindParam(':p_store_code', $stores_code);
            $stmt->bindParam(':p_user_requesting', auth()->user()->staff_no);
            $stmt->bindParam(':p_job_card', $job_card_no);
            $stmt->bindParam(':p_system_origin', $ZESCOFleetMaster);
            $stmt->bindParam(':p_fleet_req_code', $stores_requisition_number);
            $stmt->bindParam(':p_req_acc_number', $account);
            $stmt->bindParam(':p_delivery_site', $delivery_site);
            $stmt->bindParam(':p_transaction_type', $transactionType);
            $stmt->bindParam(':p_current_user', auth()->user()->staff_no);
            $stmt->execute();

            /*$results = DB::select(
                'select fn_create_stores_req (:p_ref_no,:p_reg_no,:p_store_code,:p_user_requesting,
                    :p_job_card,:p_system_origin,:p_fleet_req_code,
                    :p_req_acc_number,:p_delivery_site,
                    :p_transaction_type,:p_current_user
                    ) as value from dual',
                [
                    'p_ref_no' => $doc_no,
                    'p_reg_no' => $veh_reg_no,
                    'p_store_code' => $stores_code,
                    'p_user_requesting' => auth()->user()->staff_no,
                    'p_job_card' => $job_card_no,
                    'p_system_origin' => SystemOfOrigin::ZESCOFleetMaster,
                    'p_fleet_req_code' => $stores_requisition_number,
                    'p_req_acc_number' => $account,
                    'p_delivery_site' => $delivery_site,
                    'p_transaction_type' => $transactionType,
                    'p_current_user' => auth()->user()->staff_no,
                ]);*/

            $result = null;
            if (is_array($results) && !empty($results)) {
                $result = $results[0];
            }else{
                $result = $results;
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
            }else{
                $result = $results;
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

    /**
     * @param $ref_code
     * @return Collection
     */
    public function getArticleByCode($ref_code): mixed
    {
        $results = DB::table('SPMS_ARTICLES_VIEW')
            ->leftJoin('STOCK_MANAGEMENT_VIEW', 'SPMS_ARTICLES_VIEW.CODE_ARTICLE', '=', 'STOCK_MANAGEMENT_VIEW.CODE_ARTICLE')
            ->leftJoin('UNITS_VIEW', 'SPMS_ARTICLES_VIEW.UNIT_MEASURE', '=', 'UNITS_VIEW.code_unit')
            ->where('STOCK_MANAGEMENT_VIEW.LEVEL_TYPE', '=', '02')
            ->where('SPMS_ARTICLES_VIEW.CODE_ARTICLE', '=', $ref_code)
            ->select(
                'UNITS_VIEW.description',
                'SPMS_ARTICLES_VIEW.description as name',
                'SPMS_ARTICLES_VIEW.CODE_ARTICLE as code',
                'STOCK_MANAGEMENT_VIEW.PRICE_MAP as price'
            )
            ->get();

        return $results->first();
    }
}

<?php

namespace App\Services\Integration;

use App\Constants\SystemOfOrigin;
use App\Models\reference\Store;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDO;

class ProcurementSystemIntegrationService
{
    public static function updateRequisitions(): void
    {
        DB::executeFunction('syncRequisitions', ['p' => 3], PDO::PARAM_INT);
    }

    public function createStoresRequisition(
        $doc_no,
        $veh_reg_no,
        $stores_requisition_number,
        $account,
        $transactionType,
        $stores_code = null,
        $job_card_no = null,
        $delivery_site = null
    ): string
    {
        try {
            Log::info('Generating Stores Requisition For Request ' . $doc_no);

            $ZESCOFleetMaster = SystemOfOrigin::ZESCOFleetMaster;
            $user = auth()->user()->staff_no;

            $pdo = DB::getPdo();

            Log::info(':p_ref_no ' . $doc_no);
            Log::info(':p_reg_no ' . $veh_reg_no);
            Log::info(':p_store_code ' . $stores_code);
            Log::info(':p_user_requesting ' . $user);
            Log::info(':p_job_card ' . $job_card_no);
            Log::info(':p_system_origin ' . $ZESCOFleetMaster);
            Log::info(':p_fleet_req_code ' . $stores_requisition_number);
            Log::info(':p_req_acc_number ' . $account);
            Log::info(':p_delivery_site ' . $delivery_site);
            Log::info(':p_transaction_type ' . $transactionType);
            Log::info(':p_current_user ' . $user);

            $stmt = $pdo->prepare("begin :result := fn_create_stores_req(:p_ref_no, :p_reg_no, :p_store_code, :p_user_requesting, :p_job_card, :p_system_origin, :p_fleet_req_code, :p_req_acc_number, :p_delivery_site, :p_transaction_type, :p_current_user); end;");
            $stmt->bindParam(':result', $results, PDO::PARAM_STR, 2000);
            $stmt->bindParam(':p_ref_no', $doc_no);
            $stmt->bindParam(':p_reg_no', $veh_reg_no);
            $stmt->bindParam(':p_store_code', $stores_code);
            $stmt->bindParam(':p_user_requesting', $user);
            $stmt->bindParam(':p_job_card', $job_card_no);
            $stmt->bindParam(':p_system_origin', $ZESCOFleetMaster);
            $stmt->bindParam(':p_fleet_req_code', $stores_requisition_number);
            $stmt->bindParam(':p_req_acc_number', $account);
            $stmt->bindParam(':p_delivery_site', $delivery_site);
            $stmt->bindParam(':p_transaction_type', $transactionType);
            $stmt->bindParam(':p_current_user', $user);
            $stmt->execute();

            //$result = null;
            if (is_array($results) && !empty($results)) {
                $result = $results[0];
            } else {
                $result = $results;
            }

            Log::info($result);

            $rawJNumber = $result;

            if (str_starts_with($rawJNumber, '0')) {
                return substr($rawJNumber, 1);
            }
            return $rawJNumber;

        } catch (\Exception $e) {
            Log::error($e);
            return "";
        }
    }

    /**
     * @param string $doc_type
     * @param string $area_code
     * @return string
     * @deprecated
     */
    public function generateDocumentNumber(string $doc_type, string $area_code): string
    {
        try {
            Log::info('Generating Document For ' . $doc_type . ' and Area ' . $area_code);
            /*   $results = DB::select(
                   'select storesDocumentNumberGenerator(:, :) as value from dual',
                   ['ls_type' => $doc_type, 'ls_area' => $area_code]);
           */

            $pdo = DB::getPdo();

            $stmt = $pdo->prepare("begin :result := fn_stores_doc_no_generator(:ls_type, :ls_area); end;");
            $stmt->bindParam(':result', $results, PDO::PARAM_STR, 2000);
            $stmt->bindParam(':ls_type', $doc_type);
            $stmt->bindParam(':ls_area', $area_code);

            $result = null;
            if (is_array($results) && !empty($results)) {
                $result = $results[0];
            } else {
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
                'units_view.abbreviation',
                'SPMS_ARTICLES_VIEW.description as name',
                'SPMS_ARTICLES_VIEW.CODE_ARTICLE as code',
                'STOCK_MANAGEMENT_VIEW.PRICE_MAP as price'
            )
            ->get();

        return $results->first();
    }


    public function cancelStoresRequisition(
        $doc_no
    ): string
    {
        try {
            Log::info('Cancelling Stores Requisition For Request ' . $doc_no);

            $ZESCOFleetMaster = SystemOfOrigin::ZESCOFleetMaster;

            $pdo = DB::getPdo();
            $user_staff = auth()->user()->staff_no;

            $stmt = $pdo->prepare("begin :result := fn_cancel_stores_req(:p_ref_no, :p_current_user); end;");
            $stmt->bindParam(':result', $results, PDO::PARAM_STR, 2000);
            $stmt->bindParam(':p_ref_no', $doc_no);
            $stmt->bindParam(':p_system_origin', $ZESCOFleetMaster);
            $stmt->bindParam(':p_current_user', $user_staff);
            $stmt->execute();

            //$result = null;
            if (is_array($results) && !empty($results)) {
                $result = $results[0];
            } else {
                $result = $results;
            }

            Log::info($result);

            return $results;
            return "1";

        } catch (\Exception $e) {
            Log::error($e);
            return "";
        }
    }

    public function Store()
    {
        return Store::get();
    }

    public function createStoresReservation(
        $doc_no,
        $veh_reg_no,
        $form_order,
        $account,
        $transactionType,
        $stores_code = null,
        $job_card_no = null,
        $delivery_site = null
    )
    {
        try {
            Log::info('Generating Stores Reservation For Request ' . $doc_no);

            $ZESCOFleetMaster = SystemOfOrigin::ZESCOFleetMaster;

            $pdo = DB::getPdo();

            $user = auth()->user()->staff_no;
            $stmt = $pdo->prepare("begin :result := fn_create_stores_rsv(:p_ref_no, :p_reg_no, :p_store_code, :p_user_requesting, :p_job_card, :p_system_origin, :p_fleet_req_code, :p_req_acc_number, :p_delivery_site, :p_transaction_type, :p_current_user); end;");
            $stmt->bindParam(':p_ref_no', $doc_no);
            $stmt->bindParam(':p_reg_no', $veh_reg_no);
            $stmt->bindParam(':p_store_code', $stores_code);
            $stmt->bindParam(':p_user_requesting', $user);
            $stmt->bindParam(':p_job_card', $job_card_no);
            $stmt->bindParam(':p_system_origin', $ZESCOFleetMaster);
            $stmt->bindParam(':p_fleet_req_code', $form_order);
            $stmt->bindParam(':p_req_acc_number', $account);
            $stmt->bindParam(':p_delivery_site', $delivery_site);
            $stmt->bindParam(':p_transaction_type', $transactionType);
            $stmt->bindParam(':p_current_user', $user);
            $stmt->bindParam(':result', $results, PDO::PARAM_STR, 2000);
            $stmt->execute();

            //$result = null;
            if (is_array($results) && !empty($results)) {
                $result = $results[0];
            } else {
                $result = $results;
            }

            Log::info($result);

            $rawJNumber = $result;

            if (str_starts_with($rawJNumber, '0')) {
                return substr($rawJNumber, 1);
            }
            return $rawJNumber;

        } catch (\Exception $e) {
            Log::error($e);
            return "";
        }
    }

    public function getArticleDetailsByCode($article_code)
    {
        $results = DB::table('SPMS_ARTICLES_VIEW')
            ->leftJoin('STOCK_MANAGEMENT_VIEW', 'SPMS_ARTICLES_VIEW.CODE_ARTICLE', '=', 'STOCK_MANAGEMENT_VIEW.CODE_ARTICLE')
            ->leftJoin('UNITS_VIEW', 'SPMS_ARTICLES_VIEW.UNIT_MEASURE', '=', 'UNITS_VIEW.code_unit')
            //->where('STOCK_MANAGEMENT_VIEW.LEVEL_TYPE', '=', '02')
            ->where('SPMS_ARTICLES_VIEW.CODE_ARTICLE', '=', $article_code)
            ->select(
                'UNITS_VIEW.description',
                'units_view.abbreviation',
                'SPMS_ARTICLES_VIEW.description as name',
                'SPMS_ARTICLES_VIEW.CODE_ARTICLE as code',
                'STOCK_MANAGEMENT_VIEW.PRICE_MAP as price'
            )
            ->get();

        return $results->first();
    }
}

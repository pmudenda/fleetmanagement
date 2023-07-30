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
        DB::executeFunction("syncRequisitions", ["p" => 3], PDO::PARAM_INT);
    }

    public function createStoresRequisition(
        $docNumber,
        $vehRegNumber,
        $stores_requisition_number,
        $account,
        $transactionType,
        $stores_code = null,
        $job_card_no = null,
        $delivery_site = null
    ): string
    {
        try {
            Log::info("Generating Stores Requisition For Request " . $docNumber);

            $ZESCOFleetMaster = SystemOfOrigin::ZESCOFleetMaster;
            $user = auth()->user()->staff_no;

            $pdo = DB::getPdo();

            Log::info(":p_ref_no " . $docNumber);
            Log::info(":p_reg_no " . $vehRegNumber);
            Log::info(":p_store_code " . $stores_code);
            Log::info(":p_user_requesting " . $user);
            Log::info(":p_job_card " . $job_card_no);
            Log::info(":p_system_origin " . $ZESCOFleetMaster);
            Log::info(":p_fleet_req_code " . $stores_requisition_number);
            Log::info(":p_req_acc_number " . $account);
            Log::info(":p_delivery_site " . $delivery_site);
            Log::info(":p_transaction_type " . $transactionType);
            Log::info(":p_current_user " . $user);

            $stmt = $pdo->prepare("begin :result := fn_create_stores_req(:p_ref_no, :p_reg_no, :p_store_code, :p_user_requesting, :p_job_card, :p_system_origin, :p_fleet_req_code, :p_req_acc_number, :p_delivery_site, :p_transaction_type, :p_current_user); end;");
            $stmt->bindParam(":result", $results, PDO::PARAM_STR, 2000);
            $stmt->bindParam(":p_ref_no", $docNumber);
            $stmt->bindParam(":p_reg_no", $vehRegNumber);
            $stmt->bindParam(":p_store_code", $stores_code);
            $stmt->bindParam(":p_user_requesting", $user);
            $stmt->bindParam(":p_job_card", $job_card_no);
            $stmt->bindParam(":p_system_origin", $ZESCOFleetMaster);
            $stmt->bindParam(":p_fleet_req_code", $stores_requisition_number);
            $stmt->bindParam(":p_req_acc_number", $account);
            $stmt->bindParam(":p_delivery_site", $delivery_site);
            $stmt->bindParam(":p_transaction_type", $transactionType);
            $stmt->bindParam(":p_current_user", $user);
            $stmt->execute();

            //$result = null;
            if (is_array($results) && !empty($results)) {
                $result = $results[0];
            } else {
                $result = $results;
            }

            Log::info($result);

            $rawJNumber = $result;

            if (str_starts_with($rawJNumber, "0")) {
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
            Log::info("Generating Document For " . $doc_type . " and Area " . $area_code);

            $pdo = DB::getPdo();

            $stmt = $pdo->prepare("begin :result := fn_stores_doc_no_generator(:ls_type, :ls_area); end;");
            $stmt->bindParam(":result", $results, PDO::PARAM_STR, 2000);
            $stmt->bindParam(":ls_type", $doc_type);
            $stmt->bindParam(":ls_area", $area_code);

            $result = null;
            if (is_array($results) && !empty($results)) {
                $result = $results[0];
            } else {
                $result = $results;
            }

            Log::info($result->value);

            $rawJNumber = $result->value;
            if (str_starts_with("0", $rawJNumber)) {
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
        $stockManagement = config("tables.table_names.stockManagement");
        $articles = config("tables.table_names.articles");
        $units = config("tables.table_names.units");
        try {
            $results = DB::table("$articles")
                ->leftJoin("$stockManagement", "$articles.CODE_ARTICLE", "=", "$stockManagement.CODE_ARTICLE")
                ->leftJoin("$units", "$articles.UNIT_MEASURE", "=", "$units.code_unit")
                ->where("$stockManagement.LEVEL_TYPE", "=", "02")
                ->where("$articles.CODE_ARTICLE", "=", $ref_code)
                ->select(
                    "$units.description",
                    "$units.abbreviation",
                    "$articles.description as name",
                    "$articles.CODE_ARTICLE as code",
                    "$stockManagement.PRICE_MAP as price"
                )
                ->get();

            return $results->first();
        } catch (\Exception $e) {
            Log::info("Fetch fuel type Article");
            Log::error($e);
            return null;
        }
    }


    public function cancelStoresRequisition(
        $doc_no
    ): string
    {
        try {
            Log::info("Cancelling Stores Requisition For Request " . $doc_no);

            $ZESCOFleetMaster = SystemOfOrigin::ZESCOFleetMaster;

            $pdo = DB::getPdo();
            $user_staff = auth()->user()->staff_no;

            $stmt = $pdo->prepare("begin :result := fn_cancel_stores_req(:p_ref_no, :p_current_user); end;");
            $stmt->bindParam(":result", $results, PDO::PARAM_STR, 2000);
            $stmt->bindParam(":p_ref_no", $doc_no);
            $stmt->bindParam(":p_system_origin", $ZESCOFleetMaster);
            $stmt->bindParam(":p_current_user", $user_staff);
            $stmt->execute();

            if (is_array($results) && !empty($results)) {
                $result = $results[0];
            } else {
                $result = $results;
            }

            Log::info($result);

            return $results;
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
        $docNumber,
        $vehRegNumber,
        $formOrder,
        $account,
        $transactionType,
        $storesCode = null,
        $jobCardNumber = null,
        $deliverySite = null
    )
    {
        try {
            Log::info("Generating Stores Reservation For Request " . $docNumber);

            $ZESCOFleetMaster = SystemOfOrigin::ZESCOFleetMaster;

            $user = auth()->user()->staff_no;

            Log::info(":p_req_ref_no " . $docNumber);
            Log::info(":p_veh_reg_no " . $vehRegNumber);
            Log::info(":p_store_code " . $storesCode);
            Log::info(":p_user_requesting " . $user);
            Log::info(":p_job_card_no " . $jobCardNumber);
            Log::info(":p_system_origin " . $ZESCOFleetMaster);
            Log::info(":p_fleet_req_code " . $formOrder);
            Log::info(":p_req_acc_number " . $account);
            Log::info(":p_delivery_site " . $deliverySite);
            Log::info(":p_transaction_type " . $transactionType);
            Log::info(":p_current_user " . $user);

            $pdo = DB::getPdo();

            $stmt = $pdo->prepare("begin :result := fn_create_reservation(:p_req_ref_no, :p_veh_reg_no, :p_store_code, :p_user_requesting, :p_job_card_no, :p_system_origin, :p_fleet_req_code, :p_req_acc_number, :p_delivery_site, :p_transaction_type, :p_current_user); end;");
            $stmt->bindParam(":p_req_ref_no", $docNumber);
            $stmt->bindParam(":p_veh_reg_no", $vehRegNumber);
            $stmt->bindParam(":p_store_code", $storesCode);
            $stmt->bindParam(":p_user_requesting", $user);
            $stmt->bindParam(":p_job_card_no", $jobCardNumber);
            $stmt->bindParam(":p_system_origin", $ZESCOFleetMaster);
            $stmt->bindParam(":p_fleet_req_code", $formOrder);
            $stmt->bindParam(":p_req_acc_number", $account);
            $stmt->bindParam(":p_delivery_site", $deliverySite);
            $stmt->bindParam(":p_transaction_type", $transactionType);
            $stmt->bindParam(":p_current_user", $user);
            $stmt->bindParam(":result", $results, PDO::PARAM_STR, 2000);
            $stmt->execute();

            //$result = null;
            if (is_array($results) && !empty($results)) {
                $result = $results[0];
            } else {
                $result = $results;
            }

            Log::info($result);

            $rawJNumber = $result;

            if (str_starts_with($rawJNumber, "0")) {
                return substr($rawJNumber, 1);
            }

            return $rawJNumber;

        } catch (\Exception $e) {
            Log::error($e);
            return "";
        }
    }


    /**
     * @param $workshopReference
     * @param $regNo
     * @param $formOrder
     * @param $account
     * @param $transactionType
     * @param $storeCode
     * @param $jobCardNo
     * @param $deliverySite
     * @return mixed|string
     */
    public function createPurchaseProcess(
        $workshopReference,
        $regNo,
        $formOrder,
        $account,
        $transactionType,
        $storeCode = null,
        $jobCardNo = null,
        $deliverySite = null
    ): mixed
    {
        try {
            Log::info("Generating Purchase Process For Request " . $workshopReference);

            $system_origin = SystemOfOrigin::ZESCOFleetMaster;
            $user = auth()->user()->staff_no;

            Log::info(":p_reference " . $workshopReference);
            Log::info(":p_reg_no " . $regNo);
            Log::info(":p_store_code " . $storeCode);
            Log::info(":p_user_requesting " . $user);
            Log::info(":p_job_card_no " . $jobCardNo);
            Log::info(":p_system_origin " . $system_origin);
            Log::info(":p_delivery_site " . $deliverySite);
            Log::info(":p_form_order " . $formOrder);
            Log::info(":p_req_account " . $account);
            Log::info(":p_transaction_type " . $transactionType);
            Log::info(":p_current_user " . $user);

            $pdo = DB::getPdo();

            $stmt = $pdo->prepare("begin :result := fn_create_pur_process(
            :p_reference,
            :p_reg_no,
            :p_store_code,
            :p_user_requesting,
            :p_job_card_no,
            :p_system_origin,
            :p_form_order,
            :p_req_account,
            :p_delivery_site,
            :p_transaction_type,
            :p_current_user); end;");

            $stmt->bindParam(":p_reference", $workshopReference);
            $stmt->bindParam(":p_reg_no", $regNo);
            $stmt->bindParam(":p_store_code", $storeCode);
            $stmt->bindParam(":p_user_requesting", $user);
            $stmt->bindParam(":p_job_card_no", $jobCardNo);
            $stmt->bindParam(":p_system_origin", $system_origin);
            $stmt->bindParam(":p_form_order", $formOrder);
            $stmt->bindParam(":p_req_account", $account);
            $stmt->bindParam(":p_delivery_site", $deliverySite);
            $stmt->bindParam(":p_transaction_type", $transactionType);
            $stmt->bindParam(":p_current_user", $user);

            $stmt->bindParam(":result", $results, PDO::PARAM_STR, 2000);
            $stmt->execute();

            if (is_array($results) && !empty($results)) {
                $result = $results[0];
            } else {
                $result = $results;
            }

            Log::info($result);

            $rawJNumber = $result;

            if (str_starts_with($rawJNumber, "0")) {
                return substr($rawJNumber, 1);
            }
            return $rawJNumber;

        } catch (\Exception $e) {
            Log::error($e);
            return "";
        }
    }

    public function getArticleDetailsByCode($articleCode)
    {
        $stockManagement = config("tables.table_names.stockManagement");
        $articles = config("tables.table_names.articles");
        $units = config("tables.table_names.units");

        $results = DB::table("$articles")
            ->leftJoin("$stockManagement", "$articles.CODE_ARTICLE", "=", "$stockManagement.CODE_ARTICLE")
            ->leftJoin("$units", "$articles.UNIT_MEASURE", "=", "$units.code_unit")
            ->where("$articles.CODE_ARTICLE", "=", $articleCode)
            ->select(
                "$articles.code_article",
                "$articles.description",
                "$articles.technical_specifications",
                "$articles.price_map",
                "$stockManagement.price_map as price",
                "$stockManagement.stock_available as quantity_in_store",
                "$articles.unit_measure",
                "$units.abbreviation as abbreviation",
                "$units.description as unit_measure_name"
            )
            ->get();

        return $results->first();
    }
}

<?php

namespace App\Services\Integration;

use App\Constants\Accounts;
use App\Constants\QueryComparisonOperator;
use App\Constants\StockManagement;
use App\Constants\SystemOfOrigin;
use App\Constants\TransactionType;
use App\Models\Reference\Store;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDO;

class ProcurementSystemIntegrationService
{
    const P_CURRENT_USER = ":p_current_user";
    const P_TRANSACTION_TYPE = ":p_transaction_type";
    const P_DELIVERY_SITE = ":p_delivery_site";
    const P_REQ_ACC_NUMBER = ":p_req_acc_number";
    const P_FLEET_REQ_CODE = ":p_fleet_req_code";
    const P_SYSTEM_ORIGIN = ":p_system_origin";
    const P_USER_REQUESTING = ":p_user_requesting";
    const P_STORE_CODE = ":p_store_code";

    const RESULT = ":result";
    const P_JOB_CARD_NO = ":p_job_card_no";
    const P_REQ_REF_NO = ":p_req_ref_no";

    public static function updateRequisitions(): void
    {
        //DB::executeFunction("syncRequisitions", ["p" => 3], PDO::PARAM_INT);
    }

    public function createStoresRequisition(
        $docNumber,
        $vehRegNumber,
        $storesRequisitionNumber,
        $account,
        $storeCode = null,
        $jobCardNumber = null,
        $deliverySite = null
    ): string
    {
        try {
            $transactionType = TransactionType::STORES_REQUISITIONS;
            Log::info("Generating Stores Requisition For Request " . $docNumber);

            $systemOfOrigin = SystemOfOrigin::ZESCO_FLEET_MASTER;
            $user = auth()->user()->staff_no;

            $pdo = DB::getPdo();

            $stmt = $pdo->prepare("begin :result := fn_create_stores_req(:p_ref_no, :p_reg_no,
            :p_store_code, :p_user_requesting, :p_job_card, :p_system_origin, :p_fleet_req_code,
            :p_req_acc_number, :p_delivery_site, :p_transaction_type, :p_current_user); end;");
            $stmt->bindParam(self::RESULT, $results, PDO::PARAM_STR, 2000);
            $stmt->bindParam(":p_ref_no", $docNumber);
            $stmt->bindParam(":p_reg_no", $vehRegNumber);
            $stmt->bindParam(":p_job_card", $jobCardNumber);
            $stmt->bindParam(self::P_STORE_CODE, $storeCode);
            $stmt->bindParam(self::P_USER_REQUESTING, $user);
            $stmt->bindParam(self::P_SYSTEM_ORIGIN, $systemOfOrigin);
            $stmt->bindParam(self::P_FLEET_REQ_CODE, $storesRequisitionNumber);
            $stmt->bindParam(self::P_REQ_ACC_NUMBER, $account);
            $stmt->bindParam(self::P_DELIVERY_SITE, $deliverySite);
            $stmt->bindParam(self::P_TRANSACTION_TYPE, $transactionType);
            $stmt->bindParam(self::P_CURRENT_USER, $user);
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

    /**
     * @param string $documentType
     * @param string $areaCode
     * @return string
     * @deprecated
     */
    public function generateDocumentNumber(string $documentType, string $areaCode): string
    {
        try {
            Log::info("Generating Document For " . $documentType . " and Area " . $areaCode);

            $pdo = DB::getPdo();

            $stmt = $pdo->prepare("begin :result := fn_stores_doc_no_generator(:ls_type, :ls_area); end;");
            $stmt->bindParam(self::RESULT, $results, PDO::PARAM_STR, 2000);
            $stmt->bindParam(":ls_type", $documentType);
            $stmt->bindParam(":ls_area", $areaCode);

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
     * @param $articleCode
     * @return Collection
     */
    public function getArticleByCode($articleCode): mixed
    {
        $stockManagement = config("tables.table_names.stockManagement");
        $articles = config("tables.table_names.articles");
        $units = config("tables.table_names.units");
        try {
            $results = DB::table("$articles")
                ->leftJoin("$stockManagement", "$articles.CODE_ARTICLE",
                    QueryComparisonOperator::EQUALS,
                    "$stockManagement.CODE_ARTICLE")
                ->leftJoin("$units", "$articles.UNIT_MEASURE",
                    QueryComparisonOperator::EQUALS,
                    "$units.code_unit")
                ->where("$stockManagement.LEVEL_TYPE",
                    QueryComparisonOperator::EQUALS,
                    StockManagement::COMPANY_LEVEL
                )
                ->where("$articles.CODE_ARTICLE",
                    QueryComparisonOperator::EQUALS,
                    $articleCode)
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


    public function cancelStoresRequisition($procurementSystemReference, $cancellationJustification): string
    {
        try {
            if (empty($procurementSystemReference)) {
                return "No Procurement Reference";
            }

            Log::info("Cancelling Stores Requisition For Request " . $procurementSystemReference);

            $systemOfOrigin = SystemOfOrigin::ZESCO_FLEET_MASTER;
            $staffNumber = auth()->user()->staff_no;

            $pdo = DB::getPdo();

            $stmt = $pdo->prepare("begin :result := fn_cancel_stores_req(
            :p_ref_no,
            :p_current_user,
            :p_system_origin,
            :p_justification); end;");
            $stmt->bindParam(self::RESULT, $results, PDO::PARAM_STR, 2000);
            $stmt->bindParam(":p_ref_no", $procurementSystemReference);
            $stmt->bindParam(":p_justification", $cancellationJustification);
            $stmt->bindParam(self::P_SYSTEM_ORIGIN, $systemOfOrigin);
            $stmt->bindParam(self::P_CURRENT_USER, $staffNumber);
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

    public function store()
    {
        return Store::get();
    }

    public function createStoresReservation(
        $docNumber,
        $vehRegNumber,
        $formOrder,
        $storesCode = null,
        $jobCardNumber = null,
        $deliverySite = null
    )
    {
        try {

            $account = Accounts::MOTOR_VEHICLE_MAINTENANCE_ACCOUNT;
            $transactionType = TransactionType::STORES_REQUISITIONS;

            Log::info("Generating Stores Reservation For Request " . $docNumber);

            $originatingSystem = SystemOfOrigin::ZESCO_FLEET_MASTER;

            $user = auth()->user()->staff_no;

            Log::info("param req_ref_no " . $docNumber);
            Log::info("param veh_reg_no " . $vehRegNumber);
            Log::info("param store_code " . $storesCode);
            Log::info("param user_requesting " . $user);
            Log::info("param job_card_no " . $jobCardNumber);
            Log::info("param system_origin " . $originatingSystem);
            Log::info("param fleet_req_code " . $formOrder);
            Log::info("param req_acc_number " . $account);
            Log::info("param delivery_site " . $deliverySite);
            Log::info("param transaction_type " . $transactionType);
            Log::info("param current_user" . $user);

            $pdo = DB::getPdo();

            $stmt = $pdo->prepare("begin :result := fn_create_reservation(
              :p_req_ref_no,
              :p_veh_reg_no,
              :p_store_code,
              :p_user_requesting,
              :p_job_card_no,
              :p_system_origin,
              :p_fleet_req_code,
              :p_req_acc_number,
              :p_delivery_site,
              :p_transaction_type,
              :p_current_user
              ); end;");
            $stmt->bindParam(self::P_REQ_REF_NO, $docNumber);
            $stmt->bindParam(":p_veh_reg_no", $vehRegNumber);
            $stmt->bindParam(self::P_STORE_CODE, $storesCode);
            $stmt->bindParam(self::P_USER_REQUESTING, $user);
            $stmt->bindParam(self::P_JOB_CARD_NO, $jobCardNumber);
            $stmt->bindParam(self::P_SYSTEM_ORIGIN, $originatingSystem);
            $stmt->bindParam(self::P_FLEET_REQ_CODE, $formOrder);
            $stmt->bindParam(self::P_REQ_ACC_NUMBER, $account);
            $stmt->bindParam(self::P_DELIVERY_SITE, $deliverySite);
            $stmt->bindParam(self::P_TRANSACTION_TYPE, $transactionType);
            $stmt->bindParam(self::P_CURRENT_USER, $user);
            $stmt->bindParam(self::RESULT, $results, PDO::PARAM_STR, 2000);
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


    /**
     * @param $workshopReference
     * @param $regNo
     * @param $formOrder
     * @param $storeCode
     * @param $jobCardNo
     * @param $deliverySite
     * @return mixed|string
     */
    public function createPurchaseProcess(
        $workshopReference,
        $regNo,
        $formOrder,
        $storeCode = null,
        $jobCardNo = null,
        $deliverySite = null
    ): mixed
    {
        try {

            $account = Accounts::MOTOR_VEHICLE_MAINTENANCE_ACCOUNT;
            $transactionType = TransactionType::SERVICE_PURCHASE_REQUISITIONS;
            Log::info("Generating Purchase Process For Request " . $workshopReference);

            $systemOfOrigin = SystemOfOrigin::ZESCO_FLEET_MASTER;
            $user = auth()->user()->staff_no;

            Log::info("p reference " . $workshopReference);
            Log::info("p reg_no " . $regNo);
            Log::info("p store_code " . $storeCode);
            Log::info("p user_requesting " . $user);
            Log::info("p jobCardNo " . $jobCardNo);
            Log::info("p systemOfOrigin " . $systemOfOrigin);
            Log::info("p delivery_site " . $deliverySite);
            Log::info("p form_order " . $formOrder);
            Log::info("p req_account " . $account);
            Log::info("p transaction_type " . $transactionType);
            Log::info("p current_user " . $user);

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
            $stmt->bindParam(self::P_STORE_CODE, $storeCode);
            $stmt->bindParam(self::P_USER_REQUESTING, $user);
            $stmt->bindParam(self::P_JOB_CARD_NO, $jobCardNo);
            $stmt->bindParam(self::P_SYSTEM_ORIGIN, $systemOfOrigin);
            $stmt->bindParam(":p_form_order", $formOrder);
            $stmt->bindParam(":p_req_account", $account);
            $stmt->bindParam(self::P_DELIVERY_SITE, $deliverySite);
            $stmt->bindParam(self::P_TRANSACTION_TYPE, $transactionType);
            $stmt->bindParam(self::P_CURRENT_USER, $user);

            $stmt->bindParam(self::RESULT, $results, PDO::PARAM_STR, 2000);
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
            ->leftJoin("$stockManagement", "$articles.CODE_ARTICLE",
                QueryComparisonOperator::EQUALS,
                "$stockManagement.CODE_ARTICLE")
            ->leftJoin("$units", "$articles.UNIT_MEASURE",
                QueryComparisonOperator::EQUALS,
                "$units.code_unit")
            ->where("$articles.CODE_ARTICLE",
                QueryComparisonOperator::EQUALS,
                $articleCode)
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

    public function createStoresBookingReservation(
        $docNumber,
        $vehRegNumber,
        $formOrder,
        $account,
        $storesCode = null,
        $jobCardNumber = null,
        $deliverySite = null
    )
    {
        try {
            $transactionType = TransactionType::STORES_REQUISITIONS;
            Log::info("Generating Stores Reservation From Booking Window Request " . $docNumber);

            $originatingSystem = SystemOfOrigin::ZESCO_FLEET_MASTER;

            $user = auth()->user()->staff_no;

            Log::info(":p_req_ref_no " . $docNumber);
            Log::info(":p_veh_reg_no " . $vehRegNumber);
            Log::info(":p_store_code " . $storesCode);
            Log::info(":p_user_requesting " . $user);
            Log::info(":p_job_card_no " . $jobCardNumber);
            Log::info(":p_system_origin " . $originatingSystem);
            Log::info(":p_fleet_req_code " . $formOrder);
            Log::info(":p_req_acc_number " . $account);
            Log::info(":p_delivery_site " . $deliverySite);
            Log::info(":p_transaction_type " . $transactionType);
            Log::info(":p_current_user " . $user);

            $pdo = DB::getPdo();

            $stmt = $pdo->prepare(
                "begin :result := fn_create_booking_reservation(
             :p_req_ref_no,
             :p_veh_reg_no,
             :p_store_code,
             :p_user_requesting,
             :p_job_card_no,
             :p_system_origin,
             :p_fleet_req_code,
             :p_req_acc_number,
             :p_delivery_site,
             :p_transaction_type,
             :p_current_user); end;");

            $stmt->bindParam(self::P_REQ_REF_NO, $docNumber);
            $stmt->bindParam(":p_veh_reg_no", $vehRegNumber);
            $stmt->bindParam(self::P_STORE_CODE, $storesCode);
            $stmt->bindParam(self::P_USER_REQUESTING, $user);
            $stmt->bindParam(self::P_JOB_CARD_NO, $jobCardNumber);
            $stmt->bindParam(self::P_SYSTEM_ORIGIN, $originatingSystem);
            $stmt->bindParam(self::P_FLEET_REQ_CODE, $formOrder);
            $stmt->bindParam(self::P_REQ_ACC_NUMBER, $account);
            $stmt->bindParam(self::P_DELIVERY_SITE, $deliverySite);
            $stmt->bindParam(self::P_TRANSACTION_TYPE, $transactionType);
            $stmt->bindParam(self::P_CURRENT_USER, $user);
            $stmt->bindParam(self::RESULT, $results, PDO::PARAM_STR, 2000);
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

    public function createPurchaseProcessBooking($docNumber, $formOrder)
    {
        try {
            Log::info("Generating Stores Reservation From Booking Window Request " . $docNumber);

            $user = auth()->user()->staff_no;

            Log::info(":p_req_ref_no " . $docNumber);
            Log::info(":p_user_requesting " . $user);
            Log::info(":p_fleet_req_code " . $formOrder);
            Log::info(":p_current_user " . $user);

            $pdo = DB::getPdo();

            $stmt = $pdo->prepare("begin :result := fn_create_pur_process_conso(
             :p_req_ref_no,
             :p_fleet_req_code,
             :p_current_user,
             :p_user_requesting); end;");

            $stmt->bindParam(self::P_CURRENT_USER, $user);
            $stmt->bindParam(self::P_REQ_REF_NO, $docNumber);
            $stmt->bindParam(self::P_FLEET_REQ_CODE, $formOrder);
            $stmt->bindParam(self::P_USER_REQUESTING, $user);
            $stmt->bindParam(self::RESULT, $results, PDO::PARAM_STR, 2000);
            $stmt->execute();

            if (is_array($results) && !empty($results)) {
                $result = $results[0];
            } else {
                $result = $results;
            }

            Log::info($result);

            $rawPurchaseProcessNumber = $result;

            if (str_starts_with($rawPurchaseProcessNumber, "0")) {
                return substr($rawPurchaseProcessNumber, 1);
            }

            return $rawPurchaseProcessNumber;

        } catch (\Exception $e) {
            Log::error($e);
            return "";
        }
    }


}

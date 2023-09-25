<?php

namespace App\Services\WorkShopManagement;

use App\Constants\Articles;
use App\Constants\ErrorMessages;
use App\Constants\SystemMessages;
use App\Constants\TableColumns;
use App\Enums\RequisitionItemTypes;
use App\Exceptions\InvalidArticleType;
use App\Exceptions\MaterialReservationException;
use App\Helpers\StatusHelper;
use App\Http\Requests\WorkShopManagement\WorkshopRequisitionRequest;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class MaterialValidationService
{
    /**
     * @param mixed $requisitionPostRequest
     * @param mixed $registrationNumber
     * @param string $itemType
     * @param string $articleFieldName
     * @param string $processId
     * @return array
     * @throws InvalidArticleType
     * @throws MaterialReservationException
     */
    public function validateArticle(mixed  $requisitionPostRequest,
                                    mixed  $registrationNumber,
                                    string $itemType,
                                    string $articleFieldName,
                                    string $processId
    ): array
    {
        // check each article to make sure it's of the correct type and is
        // no active on a reservation for the same car
        $articlesTables = config("tables.table_names.articlesTables");
        $articlesMap = array();
        $itemTypeCode = $requisitionPostRequest->itemType;

        foreach ($requisitionPostRequest->get("items") as $item) {

            $article = $item[$articleFieldName];

            $key = str_replace("_", "", str_replace(" ", "", $registrationNumber))
                . str_replace("-", "", str_replace(" ", "", $article));

            if (in_array($key, array_keys($articlesMap))) {
                $message = str_replace(
                    Articles::ARTICLE_FIELD,
                    $article,
                    str_replace(
                        Articles::REG_FIELD,
                        $registrationNumber,
                        SystemMessages::DUPLICATE_ARTICLE
                    )
                );
                throw new MaterialReservationException($message);
            }

            $articlesMap[$key] = $registrationNumber;

            $query = DB::table("$articlesTables");

            $this->validateArticleGroup(
                $query,
                $itemTypeCode,
                $itemType,
                $articlesTables,
                $article,
                $registrationNumber,
                $processId
            );

        }
        return array($itemTypeCode);
    }

    /**
     * @obselete
     * @param WorkshopRequisitionRequest $requisitionPostRequest
     * @param mixed $registrationNumber
     * @param string $item_type
     * @return array
     * @throws InvalidArticleType
     * @throws MaterialReservationException
     */
    public function validateArticleOld(WorkshopRequisitionRequest $requisitionPostRequest,
                                       mixed                      $registrationNumber,
                                       string                     $item_type
    ): array
    {
        // check each article to make sure it's of the correct type and is
        // no active on a reservation for the same car
        $articles = config("tables.table_names.articles");
        $articlesMap = array();
        foreach ($requisitionPostRequest->get("items") as $item) {

            $item_type_code = $requisitionPostRequest->itemType;

            $article = $item["articleCode"];

            $key = str_replace("_", "", str_replace(" ", "", $registrationNumber))
                . str_replace("-", "", str_replace(" ", "", $article));

            if (in_array($key, array_keys($articlesMap))) {
                $message = str_replace('@article', $article,
                    str_replace('@reg',
                        $registrationNumber,
                        SystemMessages::DUPLICATE_ARTICLE));
                throw new MaterialReservationException($message);
            }

            $articlesMap[$key] = $registrationNumber;

            $query = DB::table("$articles");

            $this->validateArticleGroup(
                $query,
                $item_type_code,
                $item_type,
                $articles,
                $article,
                $registrationNumber,
                'OT'
            );

        }
        return array($item_type_code);
    }

    /**
     * @param mixed $itemTypeCode
     * @param Builder $query
     * @param string $itemType
     * @param mixed $articles
     * @param $articleCode
     * @param mixed $registrationNumber
     * @return void
     * @throws MaterialReservationException
     * @throws InvalidArticleType
     */
    public function validateArticleGroupOld(mixed   $itemTypeCode,
                                            Builder $query,
                                            string  $itemType,
                                            mixed   $articles, $articleCode,
                                            mixed   $registrationNumber): void
    {
        switch ($itemTypeCode) {
            case RequisitionItemTypes::STOCK_ITEM_CODE:
                $query->where(function ($q) use ($articles) {
                    $q->whereIn("$articles.code_group",
                        Articles::STOCK_ITEMS_GROUP);
                });

                break;
            case RequisitionItemTypes::NON_STOCK_ITEM_CODE:
                $query->where(function ($q) use ($articles) {
                    $q->where("$articles.code_group", "=", "40");
                });

                break;
            case RequisitionItemTypes::SERVICE_ITEM_CODE:
                $query->where(function ($q) use ($articles) {
                    $q->where("$articles.code_group", "=", "41");
                });
                break;
            default:
                throw new InvalidArticleType("Invalid Article Type");
        }

        $this->checkArticleType($query, $articleCode, $itemType, $registrationNumber);
    }


    /**
     * @param Builder $query
     * @param mixed $itemTypeCode
     * @param string $itemType
     * @param mixed $articlesTable
     * @param string $articleCode
     * @param mixed $registrationNumber
     * @param string $process
     * @return void
     * @throws InvalidArticleType
     * @throws MaterialReservationException
     */
    public function validateArticleGroup(Builder $query,
                                         mixed   $itemTypeCode,
                                         string  $itemType,
                                         mixed   $articlesTable,
                                         string  $articleCode,
                                         mixed   $registrationNumber,
                                         string  $process): void
    {
        switch ($itemTypeCode) {
            case RequisitionItemTypes::STOCK_ITEM_CODE:
                $query->where(function ($q) use ($articlesTable) {
                    $q->whereIn(
                        "$articlesTable.code_group",
                        "=",
                        Articles::STOCK_ITEMS_GROUP
                    );
                });

                break;
            case RequisitionItemTypes::NON_STOCK_ITEM_CODE:
                $query->where(function ($q) use ($articlesTable) {
                    $q->where(
                        "$articlesTable.code_group",
                        "=",
                        Articles::NON_STOCK_CODE_GROUP
                    );
                });

                break;
            case RequisitionItemTypes::SERVICE_ITEM_CODE:
                $query->where(function ($q) use ($articlesTable) {
                    $q->where(
                        "$articlesTable.code_group",
                        "=",
                        Articles::SERVICE_GROUP_CODE
                    );
                });
                break;
            default:
                throw new InvalidArticleType(
                    ErrorMessages::getMessage('err_0036')
                );
        }

        $this->checkArticleType($query, $articleCode, $itemType, $registrationNumber, $process);
    }

    /**
     * @param Builder $query
     * @param string $articleCode
     * @param string $itemType
     * @param mixed $registrationNumber
     * @param $process
     * @return void
     * @throws MaterialReservationException
     */
    public function checkArticleType(Builder $query,
                                     string  $articleCode,
                                     string  $itemType,
                                     mixed   $registrationNumber, $process): void
    {
        $count = $query
            ->where(
                TableColumns::ARTICLE_CODE,
                "=",
                $articleCode
            )
            ->where(
                TableColumns::STATUS,
                "=",
                StatusHelper::activeArticle()
            )->count();

        // article not found in the item type class
        if ($count == 0) {
            $message = "Article @articleCode is not a @itemType";
            if ($itemType == RequisitionItemTypes::STOCK_ITEM) {
                $articleType = "Stock Item ";
            } elseif ($itemType == RequisitionItemTypes::NON_STOCK_ITEM) {
                $articleType = "Non Stock Item ";
            } else {
                $articleType = "Service ";
            }

            throw new MaterialReservationException(
                str_replace(
                    Articles::ITEM_TYPE,
                    $articleType,
                    str_replace(
                        Articles::ARTICLE_CODE,
                        $articleCode,
                        $message
                    )
                )
            );
        }

        $activeRequests = 0;
        if ($process == 'OT') {
            $activeRequests = DB::table("gen_material_headers")
                ->join("gen_material_details",
                    "gen_material_headers.req_no",
                    "=",
                    "gen_material_details.req_no"
                )
                ->where("gen_material_details.material_code", "=", $articleCode)
                ->where("gen_material_details.reg_no", "=", $registrationNumber)
                ->whereIn("gen_material_headers.status", [
                    StatusHelper::new(),
                    StatusHelper::authorised(),
                    StatusHelper::partiallyReleased(),
                    StatusHelper::issued()
                ])->select("gen_material_headers.req_no")
                ->first();
        } else {
            $activeRequests = DB::table("wm_imprest_buy_headers header")
                ->join("wm_imprest_buy_details detail",
                    "header.imprest_reference",
                    "=",
                    "detail.header_reference"
                )
                ->where("detail.material_code",
                    "=",
                    $articleCode)
                ->where("detail.vehicle_registration",
                    "=",
                    $registrationNumber)
                ->whereIn("header.status",
                    [
                        StatusHelper::new(),
                        StatusHelper::authorised()
                    ])->select("header.imprest_reference as req_no")
                ->first();
        }

        if (!empty($activeRequests)) {
            throw new MaterialReservationException(
                str_replace(
                    Articles::REQ_NO,
                    $activeRequests->req_no,
                    str_replace(
                        Articles::REG_FIELD,
                        $registrationNumber,
                        str_replace(
                            Articles::ARTICLE_CODE,
                            $articleCode,
                            ErrorMessages::getMessage('err_0037')
                        )
                    ))
            );
        }
    }

}

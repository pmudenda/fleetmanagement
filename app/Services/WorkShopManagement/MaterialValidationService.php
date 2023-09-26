<?php

namespace App\Services\WorkShopManagement;

use App\Constants\Articles;
use App\Constants\ErrorMessages;
use App\Constants\QueryComparisonOperator;
use App\Constants\SystemMessages;
use App\Constants\TableColumns;
use App\Constants\ValidationProcess;
use App\Enums\RequisitionItemTypes;
use App\Exceptions\InvalidArticleTypeException;
use App\Exceptions\MaterialReservationException;
use App\Helpers\StatusHelper;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MaterialValidationService
{
    /**
     * @param mixed $requisitionPostRequest
     * @param mixed $registrationNumber
     * @param string $itemType
     * @param string $articleFieldName
     * @param string $process
     * @return array
     * @throws InvalidArticleTypeException
     * @throws MaterialReservationException
     */
    public function validateArticle(mixed  $requisitionPostRequest,
                                    mixed  $registrationNumber,
                                    string $itemType,
                                    string $articleFieldName,
                                    string $process
    ): array
    {
        // check each article to make sure it's of the correct type and is
        // no active on a reservation for the same car
        $articlesTables = config("tables.table_names.articles");
        Log::info("Articles Table" . config("tables.table_names.articles"));
        $articlesMap = array();
        $articleClass = $requisitionPostRequest->itemType;

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

            $finalQuery = $this->buildArticleTypeCheckingQuery(
                $query,
                $articleClass,
                $articlesTables
            );

            // move to caller
            $this->checkArticleType($finalQuery, $article, $itemType, $registrationNumber, $process);

        }

        return array($articleClass);
    }

    /**
     * @param Builder $query
     * @param mixed $articleClass
     * @param mixed $articlesTable
     * @return Builder
     * @throws InvalidArticleTypeException
     * @throws MaterialReservationException
     */
    public function buildArticleTypeCheckingQuery(Builder $query,
                                                  mixed   $articleClass,
                                                  mixed   $articlesTable
    ): Builder
    {
        Log::info("Table Passed   $articlesTable");
        Log::info("******************************************************************************");
        Log::info("                    Building Article Check Query                               ");
        Log::info("*******************************************************************************");

        switch ($articleClass) {
            case RequisitionItemTypes::STOCK_ITEM_CODE:
                $query->where(function ($q) use ($articlesTable) {
                    $q->whereIn(
                        "$articlesTable.code_group",
                        Articles::STOCK_ITEMS_GROUP
                    );
                });
                break;
            case RequisitionItemTypes::NON_STOCK_ITEM_CODE:
                $query->where(function ($q) use ($articlesTable) {
                    $q->where(
                        "$articlesTable.code_group",
                        QueryComparisonOperator::EQUALS,
                        Articles::NON_STOCK_CODE_GROUP
                    );
                });

                break;
            case RequisitionItemTypes::SERVICE_ITEM_CODE:
                $query->where(function ($q) use ($articlesTable) {
                    $q->where(
                        "$articlesTable.code_group",
                        QueryComparisonOperator::EQUALS,
                        Articles::SERVICE_GROUP_CODE
                    );
                });
                break;
            default:
                throw new InvalidArticleTypeException(
                    ErrorMessages::getMessage('err_0036')
                );
        }

        Log::info("Dumping Query Builder");
        Log::info("******************************************************************************");
        return $query;
    }

    /**
     * @param Builder $query
     * @param string $articleCode
     * @param string $itemType
     * @param mixed $registrationNumber
     * @param string $process
     * @return void
     * @throws MaterialReservationException
     */
    public function checkArticleType(Builder $query,
                                     string  $articleCode,
                                     string  $itemType,
                                     mixed   $registrationNumber,
                                     string  $process): void
    {
        Log::info("==================================================================================");
        Log::info("                    Validating Article Type                                       ");
        Log::info("==================================================================================");
        Log::info("$articleCode");
        Log::info("$registrationNumber");
        Log::info("$itemType");
        Log::info("$process");

        $count = $query
            ->where(
                TableColumns::ARTICLE_CODE,
                QueryComparisonOperator::EQUALS,
                $articleCode
            )
            ->where(
                TableColumns::STATUS,
                QueryComparisonOperator::EQUALS,
                StatusHelper::activeArticle()
            )->count();

        Log::info($count);

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
        if ($process == ValidationProcess::OTHER) {
            $activeRequests = DB::table("gen_material_headers")
                ->join("gen_material_details",
                    "gen_material_headers.req_no",
                    QueryComparisonOperator::EQUALS,
                    "gen_material_details.req_no"
                )
                ->where("gen_material_details.material_code",
                    QueryComparisonOperator::EQUALS,
                    $articleCode)
                ->where("gen_material_details.reg_no",
                    QueryComparisonOperator::EQUALS,
                    $registrationNumber)
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
                    QueryComparisonOperator::EQUALS,
                    "detail.header_reference"
                )
                ->where("detail.material_code",
                    QueryComparisonOperator::EQUALS,
                    $articleCode)
                ->where("detail.vehicle_registration",
                    QueryComparisonOperator::EQUALS,
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

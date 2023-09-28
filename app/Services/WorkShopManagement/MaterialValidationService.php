<?php

namespace App\Services\WorkShopManagement;

use App\Constants\Articles;
use App\Constants\ErrorMessages;
use App\Constants\QueryComparisonOperator;
use App\Constants\SystemMessages;
use App\Constants\TableColumns;
use App\Constants\ValidationProcess;
use App\Enums\RequisitionItemTypes;
use App\Exceptions\DuplicateArticleException;
use App\Exceptions\InvalidArticleTypeException;
use App\Exceptions\MaterialReservationException;
use App\Helpers\StatusHelper;
use App\Http\Requests\WorkShopManagement\WorkshopServiceRequisitionRequest;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MaterialValidationService
{
    /**
     * @param mixed $requisitionPostRequest
     * @param mixed $registrationNumber
     * @param string $articleClass
     * @param string $articleFieldName
     * @param string $process
     * @return array
     * @throws InvalidArticleTypeException
     * @throws MaterialReservationException
     */
    public function validateArticle(mixed  $requisitionPostRequest,
                                    mixed  $registrationNumber,
                                    string $articleClass,
                                    string $articleFieldName,
                                    string $process
    ): array
    {
        // check each article to make sure it's of the correct type and is
        // no active on a reservation for the same car
        $articlesTables = config("tables.table_names.articles");
        $articlesMap = array();

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
            $this->checkArticleType($finalQuery, $article, $articleClass, $registrationNumber, $process);

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
        Log::debug("******************************************************************************");
        Log::debug("                    Building Article Check Query                               ");
        Log::debug("*******************************************************************************");
        Log::debug("******************************" . $articleClass . "*****************************");

        switch ($articleClass) {
            case RequisitionItemTypes::STOCK_ITEM:
                $query->where(function ($q) use ($articlesTable) {
                    $q->whereIn(
                        "$articlesTable.code_group",
                        Articles::STOCK_ITEMS_GROUP
                    );
                });
                break;
            case RequisitionItemTypes::NON_STOCK_ITEM:
                $query->where(function ($q) use ($articlesTable) {
                    $q->where(
                        "$articlesTable.code_group",
                        QueryComparisonOperator::EQUALS,
                        Articles::NON_STOCK_CODE_GROUP
                    );
                });

                break;
            case RequisitionItemTypes::SERVICE:
                $query->where(function ($q) use ($articlesTable) {
                    $q->where(
                        "$articlesTable.code_group",
                        QueryComparisonOperator::EQUALS,
                        Articles::SERVICE_GROUP_CODE
                    )->where("$articlesTable.code_subgroup",
                        QueryComparisonOperator::EQUALS,
                        "02");
                });
                break;
            default:
                throw new InvalidArticleTypeException(
                    ErrorMessages::getMessage('err_0036')
                );
        }

        Log::debug("Dumping Query Builder");
        Log::debug("******************************************************************************");
        return $query;
    }

    /**
     * @param Builder $query
     * @param string $codeArticle
     * @param string $articleClass
     * @param mixed $registrationNumber
     * @param string $process
     * @return void
     * @throws MaterialReservationException
     */
    public function checkArticleType(Builder $query,
                                     string  $codeArticle,
                                     string  $articleClass,
                                     mixed   $registrationNumber,
                                     string  $process): void
    {
        Log::debug("==================================================================================");
        Log::debug("                    Validating Article Type                                       ");
        Log::debug("==================================================================================");
        Log::debug("$codeArticle");
        Log::debug("$registrationNumber");
        Log::debug("$articleClass");
        Log::debug("$process");

        $count = $query
            ->where(
                TableColumns::ARTICLE_CODE,
                QueryComparisonOperator::EQUALS,
                $codeArticle
            )
            ->where(
                TableColumns::STATUS,
                QueryComparisonOperator::EQUALS,
                StatusHelper::activeArticle()
            )->count();

        Log::debug($count);

        if ($count == 0) {
            $message = "Article @articleCode is not a @itemType";
            if ($articleClass == RequisitionItemTypes::STOCK_ITEM) {
                $articleType = "Stock Item ";
            } elseif ($articleClass == RequisitionItemTypes::NON_STOCK_ITEM) {
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
                        $codeArticle,
                        $message
                    )
                )
            );
        }


        if ($process == ValidationProcess::OTHER) {
            $activeRequests = DB::table("gen_material_headers")
                ->join("gen_material_details",
                    "gen_material_headers.req_no",
                    QueryComparisonOperator::EQUALS,
                    "gen_material_details.req_no"
                )
                ->where("gen_material_details.material_code",
                    QueryComparisonOperator::EQUALS,
                    $codeArticle)
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
                    $codeArticle)
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
                            $codeArticle,
                            ErrorMessages::getMessage('err_0037')
                        )
                    ))
            );
        }
    }

    /**
     * @param WorkshopServiceRequisitionRequest $requisitionPostRequest
     * @param string $itemType
     * @param mixed $registrationNumber
     * @return array
     * @throws MaterialReservationException
     * @throws DuplicateArticleException
     * @throws InvalidArticleTypeException
     */
    public function validateServiceArticle(
        WorkshopServiceRequisitionRequest $requisitionPostRequest,
        string                            $articleClass,
        mixed                             $registrationNumber
    ): array
    {
        // check each article to make sure it's of the correct type and is no active on a reservation for the same car
        $articlesTable = config("tables.table_names.articles");
        $articlesMap = array();
        //= $requisitionPostRequest->itemType;

        foreach ($requisitionPostRequest->get("items") as $item) {

            $article = $item["service_article"];

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

                throw new DuplicateArticleException($message);
            }

            $articlesMap[$key] = $registrationNumber;

            $query = DB::table("$articlesTable");

            $finalQuery = $this->buildArticleTypeCheckingQuery(
                $query,
                $articleClass,
                $articlesTable
            );

            // move to caller
            $this->checkArticleType(
                $finalQuery,
                $article,
                $articleClass,
                $registrationNumber,
                ValidationProcess::OTHER
            );
        }

        return array($articleClass);
    }


    /**
     * @param mixed $articleClass
     * @param $codeArticle
     * @param mixed $registrationNumber
     * @return void
     * @throws InvalidArticleTypeException
     * @throws MaterialReservationException
     */
    public function validateSelectedServiceArticles(
        mixed $articleClass,
              $codeArticle,
        mixed $registrationNumber): void
    {
        $articlesTable = config("tables.table_names.articles");
        $query = DB::table("$articlesTable");

        $finalQuery = $this->buildArticleTypeCheckingQuery(
            $query,
            $articleClass,
            $articlesTable
        );

        $this->checkArticleType(
            $finalQuery,
            $codeArticle, $articleClass,
            $registrationNumber,
            ValidationProcess::OTHER
        );
    }

}

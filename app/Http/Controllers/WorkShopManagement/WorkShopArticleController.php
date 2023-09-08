<?php

namespace App\Http\Controllers\WorkShopManagement;

use App\Constants\ErrorMessages;
use App\Enums\RequisitionItemTypes;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WorkShopArticleController extends Controller
{
    public function searchArticle(Request $request): JsonResponse
    {
        try {

            if (empty($request->get("type_article"))) {
                return response()->json([
                    "success" => false,
                    "items" => [],
                    "total_count" => 0
                ]);
            }

            $search = trim(strtoupper($request->get("search")));

            $query = $this->getArticlesQueryBuilder($request);

            $stockManagement = config("tables.table_names.stockManagement");
            $articles = config("tables.table_names.articles");
            $units = config("tables.table_names.units");

            $query->where(function ($query) use ($search, $articles) {
                $query->orWhere("$articles.code_article", "like", "%{$search}%")
                    ->orWhere("$articles.description", "like", "%{$search}%");
            });

            $procurementArticles = $query
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
                ->orderBy("$articles.description")
                ->get();

            return response()->json([
                "success" => !empty($procurementArticles),
                "items" => $procurementArticles,
                "total_count" => $procurementArticles->count()
            ]);

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                "success" => false,
                "items" => [],
                "total_count" => 0,
                "message" => ErrorMessages::getMessage("err_0005")
            ]);
        }
    }

    public function getArticlesByType(Request $request): JsonResponse
    {
        try {

            if (empty($request->get("type_article"))) {
                return response()->json([
                    "success" => false,
                    "items" => [],
                    "total_count" => 0
                ]);
            }

            $query = $this->getArticlesQueryBuilder($request);

            $stockManagement = config("tables.table_names.stockManagement");
            $articles = config("tables.table_names.articles");
            $units = config("tables.table_names.units");

            $procurementArticles = $query
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
                ->orderBy("$articles.description")
                ->get();

            return response()->json([
                "success" => !empty($procurementArticles),
                "items" => $procurementArticles,
                "total_count" => $procurementArticles->count()
            ]);

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                "success" => false,
                "items" => [],
                "total_count" => 0,
                "message" => ErrorMessages::getMessage("err_0005")
            ]);
        }
    }

    /**
     * @param Request $request
     * @return Builder
     */
    public function getArticlesQueryBuilder(Request $request): Builder
    {
        $stockManagement = config("tables.table_names.stockManagement");
        $articles = config("tables.table_names.articles");
        $units = config("tables.table_names.units");

        $query = DB::table("$articles")
            ->leftJoin("$units",
                "$articles.unit_measure",
                "=",
                "$units.code_unit")
            ->leftJoin("$stockManagement",
                "$articles.code_article",
                "=",
                "$stockManagement.code_article");

        $itemType = $request->get("type_article");
        $storeCode = $request->get("store_code");

        if ($itemType == RequisitionItemTypes::STOCK_ITEM_CODE) {
            $query->where(function ($q) use ($storeCode, $stockManagement, $articles) {
                $q->whereIn("$articles.code_group", ["01", "04", "30"]);
                $q->where("$stockManagement.code_store", "=", $storeCode);
            });
        } elseif ($itemType == RequisitionItemTypes::NON_STOCK_ITEM_CODE) {
            $query->where(function ($q) use ($articles) {
                $q->where("$articles.code_group", "=", "40");
                $q->where("$articles.code_subgroup", "=", "07");
            });
        } elseif ($itemType == RequisitionItemTypes::SERVICE_ITEM_CODE) {
            $query->where(function ($q) use ($articles) {
                $q->where("$articles.code_group", "=", "41");
                $q->where("$articles.code_subgroup", "=", "02");
            });
        }

        $query->where("$articles.type_article", "=", $request->get("type_article"));
        return $query;
    }

}

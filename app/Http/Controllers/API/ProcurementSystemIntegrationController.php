<?php

namespace App\Http\Controllers\API;

use App\Constants\ErrorMessages;
use App\Models\Reference\Article;
use App\Models\Reference\BatteryModel;
use App\Models\Reference\PurchaseOrder;
use App\Models\Reference\TyreSizesModel;
use App\Services\Integration\ProcurementSystemIntegrationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProcurementSystemIntegrationController extends \App\Http\Controllers\Controller
{
    private ProcurementSystemIntegrationService $procurementSystemIntegrationService;

    public function __construct(ProcurementSystemIntegrationService $procurementSystemIntegrationService)
    {
        $this->procurementSystemIntegrationService = $procurementSystemIntegrationService;
    }

    public function verifyPurchaseOrder(Request $request): JsonResponse
    {

        try {
            $document_number = $request->get('document_number');
            if (empty($document_number)) {
                return response()->json([
                    'state' => 'false',
                    'payload' => [],
                    'message' => 'Bad request, data missing'
                ]);
            }
            $purchaseOrder = PurchaseOrder::where('document_no', '=', $document_number)->get();
            if (empty($purchaseOrder)) {
                return response()->json([
                    'state' => 'false',
                    'payload' => [],
                    'message' => 'Invalid Purchase Order Number, The Purchase Order Number did not match any record'
                ]);
            }
            return response()->json([
                'state' => 'success',
                'payload' => $purchaseOrder,
                'message' => 'Purchase Order Data Retrieved Successfully'
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'state' => 'false',
                'payload' => [],
                'message' => ErrorMessages::getMessage('err_0005')
            ]);
        }
    }

    public function getSuppliers(): JsonResponse
    {
        try {
            $suppliers = PurchaseOrder::distinct()
                ->orderBy('name_of_supplier', 'asc')
                ->get(['code_supplier', 'name_of_supplier']);

            return response()->json([
                'state' => 'success',
                'payload' => $suppliers
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'payload' => [],
                'message' => ErrorMessages::getMessage('err_0005')
            ]);
        }
    }

    public function fuelTypes(): JsonResponse
    {
        return response()->json([
            'payload' => Article::whereIn(
                'code_article',
                config('fuelarticles.articles')
            )->get(['code_article', 'description'])
        ]);
    }

    public function getArticleDetails(Request $request): JsonResponse
    {
        try {
            $codeArticle = $request->get('code_article');

            Log::info('Code Article ' . $codeArticle);

            $procurementArticles = $this->procurementSystemIntegrationService->getArticleDetailsByCode($codeArticle);

            return response()->json([
                'success' => !empty($procurementArticles),
                'payload' => $procurementArticles,
            ]);

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'payload' => [],
                'message' => ErrorMessages::getMessage('err_0005')
            ]);
        }
    }

    public function getBatterySizes(): JsonResponse
    {
        try {
            $data = BatteryModel::where('description', 'like', '%AUTO%BATTERY%')
                ->orderBy('code_article', 'asc')
                ->get();
            return response()->json([
                'state' => 'success',
                'payload' => $data
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'payload' => []
            ]);
        }
    }

    public function getTyreSizes(): JsonResponse
    {
        try {
            $data = TyreSizesModel::get();
            return response()->json([
                'state' => 'success',
                'payload' => $data
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'payload' => []
            ]);
        }
    }

}

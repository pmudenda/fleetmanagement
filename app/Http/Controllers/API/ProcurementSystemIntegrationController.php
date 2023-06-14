<?php

namespace App\Http\Controllers\API;

use App\Constants\ErrorMessages;
use App\Models\reference\Article;
use App\Models\reference\PurchaseOrders;
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
            $purchaseOrder = PurchaseOrders::where('document_no', '=', $document_number)->get();
            if (empty($purchaseOrder)) {
                return response()->json([
                    'state' => 'false',
                    'payload' => [],
                    'message' => 'Invalid Purchase Order Number, The Purchase Order Number did not match any record'
                ]);
            }
            return response()->json([
                'state' => empty($purchaseOrder) ? 'erorr' : 'success',
                'payload' => $purchaseOrder,
                'message' => empty($purchaseOrder) ? " Data could not be retrieved" : 'Data Retrieved'
            ]);
        } catch (Exception $e) {
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
            $suppliers = PurchaseOrders::distinct()->get(['code_supplier', 'name_of_supplier']);;
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
            'payload' => Article::whereIn('code_article', ['300101-0002', '300101-0001'])
                ->get(['code_article', 'description'])
        ]);
    }

    public function getArticleDetails(Request $request): JsonResponse
    {
        try {

            $procurementArticles = $this->procurementSystemIntegrationService->getArticleDetailsByCode($request->get('type_article'));

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

}

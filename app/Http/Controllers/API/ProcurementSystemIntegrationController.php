<?php

namespace App\Http\Controllers\API;

use App\Constants\ErrorMessages;
use App\Constants\QueryComparisonOperator;
use App\Constants\SystemMessages;
use App\Exceptions\InvalidPurchaseOrderNumber;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\Reference\Article;
use App\Models\Reference\BatteryModel;
use App\Models\Reference\PurchaseOrder;
use App\Models\Reference\TyreSizesModel;
use App\Services\Integration\ProcurementSystemIntegrationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

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

            $documentNumber = $request->get('document_number');
            if (empty($documentNumber)) {
                throw new BadRequestException('Bad request, data missing');
            }

            $purchaseOrder = PurchaseOrder::where('document_no',
                QueryComparisonOperator::EQUALS,
                $documentNumber)->get();
            if (empty($purchaseOrder)) {
                throw new InvalidPurchaseOrderNumber(
                    'Invalid Purchase Order Number,
                The Purchase Order Number did not match any record'
                );
            }

            return response()->json(
                FleetMasterJsonResponse::response(
                    'success',
                    true,
                    SystemMessages::PURCHASE_ORDER_RETRIEVED,
                    $purchaseOrder
                ));

        } catch (Exception $e) {
            $message = ErrorMessages::getMessage('err_0005');
            if ($e instanceof BadRequestException ||
                $e instanceof InvalidPurchaseOrderNumber) {
                $message = $e->getMessage();
            }
            Log::error($e->getMessage());

            return response()->json(
                FleetMasterJsonResponse::response(
                    'false',
                    false,
                    $message
                )
            );
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

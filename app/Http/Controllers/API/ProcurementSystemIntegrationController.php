<?php

namespace App\Http\Controllers\API;

use App\Models\reference\Article;
use App\Models\reference\PurchaseOrders;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProcurementSystemIntegrationController extends \App\Http\Controllers\Controller
{
    public function verifyPurchaseOrder(Request $request): JsonResponse
    {
        $document_number = $request->get('document_number');
        if (empty($document_number)) {
            return response()->json([
                'state' => 'success',
                'payload' => [],
                'message' => 'Bad request, data missing'
            ]);
        }

        $purchaseOrder = PurchaseOrders::where('document_no', '=', $document_number)->get();

        return response()->json([
            'state' => 'success',
            'payload' => $purchaseOrder,
            'message' => 'Data Retrieved'
        ]);
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
                'payload' => []
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

}

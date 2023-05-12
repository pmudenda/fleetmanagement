<?php

namespace App\Http\Controllers\API;

use App\Models\reference\PurchaseOrders;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
            $suppliers = PurchaseOrders::get();
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
}

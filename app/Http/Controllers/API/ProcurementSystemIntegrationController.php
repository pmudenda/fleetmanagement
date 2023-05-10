<?php

namespace App\Http\Controllers\API;

use App\Models\reference\PurchaseOrders;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProcurementSystemIntegrationController extends \App\Http\Controllers\Controller
{
    public function verify(Request $request): JsonResponse
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
            'message' => ''
        ]);
    }
}

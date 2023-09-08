<?php

namespace App\Http\Controllers\WorkShopManagement;

use App\Constants\WorkflowModules;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkShopManagement\PettyCashItems;
use App\Models\WorkShopManagement\ImprestBuyDetail;
use App\Models\WorkShopManagement\ImprestBuyHeader;
use App\Services\Workflow\DocumentNumberGenerationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImprestBuysController extends Controller
{
    public function saveImprestBuyItems(PettyCashItems $request): JsonResponse
    {
        try {
            $user = auth()->user();
            $imprestReferenceNumber = DocumentNumberGenerationService::generateReferenceNumber(
                WorkflowModules::IMPREST_BUY
            );

            DB::beginTransaction();
            ImprestBuyHeader::firstOrCreate(
                [
                    'work_order_number' => $request->get('imprestProjectNumber'),
                ],
                [
                    'imprest_reference' => $imprestReferenceNumber,
                    'cost_center' => $user->cc_code,
                    'business_unit_code' => $user->bc_code,
                    'work_order_number' => $request->get('imprestProjectNumber'),
                    'total_payment' => floatval($request->get('totalPayment')),
                    //'code' => $pettyCashSystemReference,
                    'zqms_ref_no' => $request->get('imprestZQMSReference'),
                    'status' => StatusHelper::new(),
                    'name' => $user->name,
                    'staff_no' => $user->staff_no,
                    'claim_date' => Carbon::now(),
                    'created_by' => $user->staff_no,
                ]);

            foreach ($request->get('items') as $item) {
                ImprestBuyDetail::create(
                    [
                        'header_reference' => $imprestReferenceNumber,
                        'vehicle_registration' => $item['imprestVehicleRegistration'],
                        'material_code' => $item['imprestArticleCode'],
                        'description' => $item['imprestArticleDescription'],
                        'specification' => $item['imprestArticleDescription'],
                        'quantity' => (int)$item['imprestItemQty'],
                        'unit_of_measure' => $item['imprestItemUnitOfMeasure'],
                        'unit_price' => floatval($item['imprestItemUnitPrice']),
                        'total_price' => floatval($item['imprestItemTotalPrice']),
                        'created_by' => $user->staff_no
                    ]
                );
            }

            DB::commit();

            $eformsPettyCashUrl = config('systeminfo.petty_cash_url');
            Log::info("Posting Data To $eformsPettyCashUrl");

            Log::info("Logging Response From Petty Cash System");

            return response()->json(
                [
                    'success' => true,
                    'payload' => $request->all()
                ]
            );

        } catch (Exception $e) {
            Log::error($e);
            return response()->json(
                [
                    'success' => false,
                    'payload' => $request->all()
                ]
            );
        }
    }

}

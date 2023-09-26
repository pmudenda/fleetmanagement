<?php

namespace App\Http\Controllers\WorkShopManagement;

use App\Constants\ErrorMessages;
use App\Constants\SystemMessages;
use App\Enums\RequisitionItemTypes;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Models\MaterialDetail;
use App\Models\MaterialHeader;
use App\Models\WorkShopManagement\JobCardHeader;
use App\Models\WorkShopManagement\WorkShopMaterial;
use App\Models\WorkShopManagement\WorkShopServiceModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JobCardLinkingController extends Controller
{
    public function attachReservedArticlesToJobCard(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $reference = $request->get('jobCardNumber');
            $documentIds = $request->get('items');
            Log::debug("Attaching Articles on $reference");
            $requestIds = [];

            foreach ($documentIds as $documentId) {
                $requestIds[] = $documentId['requestId'];
                Log::debug("Article " . $documentId['requestId']);
            }

            $workOrder = JobCardHeader::where("job_card_no", "=", $reference)
                ->first();

            $user = Auth::user();

            $materials = MaterialDetail::whereIn('id', $requestIds)->get();

            $regVehicles = array();

            foreach ($materials as $material) {
                Log::debug("Attaching Article :" . $material->material_code);

                $materialHeader = MaterialHeader::where('req_no', '=', $material->req_no)->first();

                Log::debug("Item Type :" . $materialHeader->item_type);

                $key = $material->reg_no;
                if (!in_array($key, array_keys($regVehicles))) {
                    $regVehicles[$key] = $key;
                }

                switch ($materialHeader->item_type) {
                    case RequisitionItemTypes::STOCK_ITEM:
                        Log::debug("Article Group:" . $materialHeader->item_type);
                        WorkShopMaterial::firstOrCreate(
                            [
                                "wshp_act_code" => $workOrder->wshp_act_code,
                                "workshop_code" => $workOrder->workshop_code,
                                "mat_code" => $material->material_code,
                            ],
                            [
                                'sch_flouted' => 'N',
                                "form_order" => $materialHeader->form_order,
                                "st_pur" => $materialHeader->st_pur,
                                "evaluation" => "Y",
                                "date_mat" => Carbon::now(),
                                "unit_of_measure" => $material->unit_of_measure,
                                "quantity" => $material->quantity,
                                "amount" => $material->amount,
                                "price" => $material->price,
                                "store_code" => $material->stores_code,
                                "supplier_code" => $material->supplier_code,
                                "veh_reg_no" => $material->reg_no,
                                "specifications" => $material->specifications,
                                "requested_by" => $material->created_by,
                                "status" => StatusHelper::new(),
                                "created_by" => $user->staff_no,
                            ]);
                        break;
                    case RequisitionItemTypes::SERVICE:
                    case RequisitionItemTypes::NON_STOCK_ITEM:
                        Log::debug("Article Group :" . $materialHeader->item_type);
                        WorkShopServiceModel::firstOrCreate(
                            [
                                "wshp_act_code" => $workOrder->wshp_act_code,
                                "wshp_code" => $workOrder->workshop_code,
                                "movt_no" => $materialHeader->form_order,
                                "mat_code" => $material->material_code,
                            ],
                            [
                                "date_send" => Carbon::now(),
                                "evaluation" => "Y",
                                "unit_of_measure" => $material->unit_of_measure,
                                "quantity" => $material->quantity,
                                "amount_est" => $material->amount,
                                "price" => $material->price,
                                "store_code" => $material->stores_code,
                                "code_office" => $materialHeader->purchase_office,
                                "supp_code" => $materialHeader->supplier_code,
                                "veh_reg_no" => $material->reg_no,
                                "specifications" => $material->specifications,
                                "originator" => $user->staff_no,
                                "stf_number" => $materialHeader->st_pur,
                                "status" => $materialHeader->status,
                                "created_by" => $user->id
                            ]
                        );
                        break;
                    default:
                        break;
                }

                $material->claimed = 'Y';
                $material->save();
            }

            DB::commit();
            return response()->json([
                'payload' => [],
                'success' => true,
                'message' => SystemMessages::ARTICLES_ATTACHED_SUCCESSFULLY
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'payload' => [],
                'success' => false,
                'message' => ErrorMessages::getMessage('err_0005')
            ]);
        }
    }
}

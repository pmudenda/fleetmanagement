<?php

namespace App\Services\Requestions;

use App\Http\Requests\FuelRequisitionPostRequest;
use App\Models\MaterialDetail;
use App\Models\MaterialHeader;
use App\Services\Workflow\ReferenceNumberGeneratorService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class FuelRequisitionService
{

    public function processRequest(FuelRequisitionPostRequest $request): JsonResponse
    {
        //vehicle_registration
        // find last request and validate odometer readings
        DB::beginTransaction();

        $user = Auth()->user();
        $documentRef = ReferenceNumberGeneratorService::generateReferenceNumber(
            "ZFMFUE",
            1,
        );
        $valid_to = null;

        if ($request->requisition_type == '011') {
            $valid_to = Carbon::createFromFormat('d/m/Y', $request->return_date);
            $valid_from = Carbon::createFromFormat('d/m/Y', $request->departure_date);

        } else {
            $valid_to = Carbon::createFromFormat('d/m/Y', $request->next_fuel_date);
            $valid_from = Carbon::createFromFormat('d/m/Y', $request->request_date);
        }
        //dd(Carbon::parse($valid_from));

        $procurementRef = 'J01' . $user->area_code ?? 'GR' . random_int(100000, 999999);
        MaterialHeader::create(
            [
                'proc_ref' => $procurementRef,
                'st_pur' => $procurementRef,
                'req_no' => $documentRef,
                'reg_no' => $request->vehicle_registration,
                'valid_date_from' => $valid_from,
                'valid_date_to' => $valid_to,
                'odometer' => $request->odometer_reading,
                'town_from' => $request->town_from,
                'town_to' => $request->town_to,
                'date_created' => Carbon::now(),
                'created_by' => $user->id,
                'requested_by' => $user->name,
                'comments' => $request->justification
            ]
        );

        MaterialDetail::create([
            'req_no' => $documentRef,
            'material_code' => $request->material_article_code,
            'quantity' => $request->material_quantity,
            'unit_of_measure' => $request->unit_of_measure,
            'specifications' => $request->material_description,
            'project_code' => $request->projectCode,
            //'supplier_code',
            'cost_centre' => $request->cost_centre_code,
            'reg_no' => $request->vehicle_registration,
            'amount' => $request->material_amount,
            'price' => $request->material_price,
            'date_created' => Carbon::now(),
            'created_by' => $user->id
        ]);
        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Requisition  Submitted Successfully..'
        ]);
    }
}

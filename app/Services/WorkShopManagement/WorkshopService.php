<?php

namespace App\Services\WorkShopManagement;

use App\Enums\ConfigurationTypes;
use App\Enums\Modules;
use App\Models\configurations\GeneralTableConfigurations;
use App\Models\WorkShopManagement\JobCardHeader;
use App\Services\Workflow\DocumentNumberGenerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class WorkshopService
{

    public function createJobCard(Request $request)
    {
        $user = auth()->user();

        if ($request->has('job_card_number') && empty($request->get('job_card_number'))) {
            // update the information
            $details = JobCardHeader::where('job_card_no', '=', $request->get('job_card_number'))->orderBy('id', 'desc')->first();

            $details->veh_reg = $request->get('vehicle_registration');
            $details->date_in = Carbon::parse($request->get('date_of_req'));
            $details->workshop_code = $request->get('workshop');
            $details->time_in = Carbon::parse($request->get('timeIn'))->format('H:i:s');
            $details->repair_type = $request->get('repairType');
            //$details->received_by = $user->staff_no;
            //$details->receiving_section = $section->code;
            $details->accident_ref = $request->get('accident_number');
            $details->millage_in = $request->get('current_odometer');
            $details->fuel_level_in = $request->get('fuel_level');
            $details->driver_in = $request->get('driver_staff_number');
            $details->modified_by = $user->id;
            $details->save();

            return $details;
        }

        //$doc_number = DocumentNumberGenerationService::generateReferenceNumber(Modules::JOB_CARD);
        $doc_number = DocumentNumberGenerationService::generateReferenceNumber(Modules::JOB_CARD);
        //$doc_number = '983738378363';

        $section = GeneralTableConfigurations::where('name', '=', 'RECEPTION')
            ->where('type', ConfigurationTypes::WORK_SHOP_SECTION)
            ->first();
        if (empty($section)) {
            Log::info("Receiving Section Not Found");
        }

        $data = [
            'veh_reg' => $request->get('vehicle_registration'),
            'date_in' => Carbon::parse($request->get('date_of_req')),
            'workshop_code' => $request->get('workshop'),
            'time_in' => Carbon::parse($request->get('timeIn'))->format('H:i:s'),
            'repair_type' => $request->get('repairType'),
            'received_by' => $user->staff_no,
            'receiving_section' => $section->code,
            'accident_ref' => $request->get('accident_number'),
            'millage_in' => $request->get('current_odometer'),
            'fuel_level_in' => $request->get('fuel_level'),
            'driver_in' => $request->get('driver_staff_number'),
            'job_card_no' => $doc_number,
            'workshop_doc_no' => $doc_number,
            'created_by' => $user->id
        ];

        return JobCardHeader::create($data);
    }
}

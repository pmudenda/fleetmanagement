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
        //$doc_number = DocumentNumberGenerationService::generateReferenceNumber(Modules::JOB_CARD);
        //$doc_number = DocumentNumberGenerationService::generateReferenceNumber(Modules::JOB_CARD);
        $doc_number = '983738378363';
        $user = auth()->user();

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
            'workshop_doc_no' => $doc_number
        ];

        return JobCardHeader::create($data);
    }
}

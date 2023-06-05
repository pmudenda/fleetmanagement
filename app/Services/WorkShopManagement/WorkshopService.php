<?php

namespace App\Services\WorkShopManagement;

use App\Enums\ConfigurationTypes;
use App\Enums\Modules;
use App\Helpers\StatusHelper;
use App\Http\Requests\JobCardRequest;
use App\Models\configurations\ConfigAccessories;
use App\Models\configurations\GeneralTableConfigurations;
use App\Models\WorkShopManagement\JobCardHeader;
use App\Models\WorkShopManagement\WorkShopVehicleAccessories;
use App\Services\Workflow\DocumentNumberGenerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WorkshopService
{

    public function createJobCard(JobCardRequest $request)
    {
        $user = auth()->user();

        ///$receiverParts = explode('|', $request->get('service_advisor'));
        if ($request->has('job_card_number') && !empty($request->get('job_card_number')) && $request->get('job_card_number') != 0) {
            // update the information
            $details = JobCardHeader::where('job_card_no', '=', $request->get('job_card_number'))->orderBy('id', 'desc')
                ->first();

            $details->veh_reg = $request->get('vehicle_registration');
            $details->workshop_code = $request->get('workshop');
            $details->repair_type = $request->get('repairType');

            //$details->date_in = Carbon::parse(trim($request->get('date_of_req')));
            //$details->time_in = Carbon::parse(trim($request->get('timeIn')))->format('H:i:s');
            //$details->received_by = $user->staff_no;
            //$details->receiving_section = $section->code;

            $details->accident_ref = $request->get('accident_number') ?? 'N/A';
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
            'date_in' => Carbon::now(), Carbon::createFromFormat('Y-m-d', trim($request->get('date_of_req'))),
            'workshop_code' => $request->get('workshop'),
            'time_in' => Carbon::now(),//(trim($request->get('timeIn')))->format('H:i:s'),
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

    public function getJobCardDetails(mixed $reference)
    {
        //        //$details = JobCardHeader::where('job_card_no', '=', $reference)->orderBy('id', 'desc')->first();
        $query = DB::table('WKS_JOB_CARD_HEADER')
            ->leftJoin('SEC_USERS', 'WKS_JOB_CARD_HEADER.received_by', '=', 'SEC_USERS.staff_no')
            ->leftJoin('CONFIG_GENERAL_TABLES', 'WKS_JOB_CARD_HEADER.receiving_section', '=', 'CONFIG_GENERAL_TABLES.code')
            ->where('CONFIG_GENERAL_TABLES.type', '=', ConfigurationTypes::WORK_SHOP_SECTION)
            ->where('WKS_JOB_CARD_HEADER.job_card_no', '=', $reference)
            ->select('WKS_JOB_CARD_HEADER.*', 'CONFIG_GENERAL_TABLES.name as section_in_name', 'SEC_USERS.name as service_advisor')
            ->get();

        return $query->first();
    }

    public function createJobCardAccessories(Request $request): void
    {
        $job_card_voucher = $request->get('job_card_voucher');
        $accessoryNames = ConfigAccessories::where('status', '=', StatusHelper::active())
            ->get();

        foreach ($accessoryNames as $accessoryName) {
            $accessoryCode = $accessoryName->code;

            $response = $request->get('field_' . trim($accessoryCode));
            $remarks = $request->get('comment_' . trim($accessoryCode));

            Log::info($accessoryCode . ' ' . $response . ' ' . $remarks);

            WorkShopVehicleAccessories::updateOrCreate(
                [
                    'job_card_no' => trim($job_card_voucher),
                    'code' => trim($accessoryCode),
                ],
                [
                    //'job_card_no' => $job_card_voucher,
                    //'code' => $accessoryCode,
                    'name' => $accessoryName->name,
                    'remarks' => $remarks,
                    'is_present' => $response
                ]
            );
        }
    }

    public function createJobCardDefects(Request $request)
    {
    }

    public function getJobCardHeader(): Collection
    {
        return DB::table('WKS_JOB_CARD_HEADER')
            ->leftJoin('SEC_USERS', 'WKS_JOB_CARD_HEADER.received_by', '=', 'SEC_USERS.staff_no')
            ->leftJoin('CONFIG_GENERAL_TABLES', 'WKS_JOB_CARD_HEADER.receiving_section', '=', 'CONFIG_GENERAL_TABLES.code')
            ->leftJoin('CONFIG_GENERAL_TABLES as config', 'WKS_JOB_CARD_HEADER.repair_type', '=', 'config.code')
            ->leftJoin('CONFIG_WORKSHOP', 'WKS_JOB_CARD_HEADER.receiving_section', '=', 'CONFIG_WORKSHOP.workshop_code')
            ->where('CONFIG_GENERAL_TABLES.type', '=', ConfigurationTypes::WORK_SHOP_SECTION)
            ->where('config.type', '=', ConfigurationTypes::REPAIR_TYPE)
            //->where('config.code', '=', )
            ->select('WKS_JOB_CARD_HEADER.*',
                'CONFIG_WORKSHOP.workshop_name',
                'config.name as repair_type_name',
                'CONFIG_GENERAL_TABLES.name as section_in_name',
                'SEC_USERS.name as service_advisor')
            ->get();

    }
}

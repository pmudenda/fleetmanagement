<?php

namespace App\Services\WorkShopManagement;

use App\Enums\ConfigurationTypes;
use App\Enums\Modules;
use App\Helpers\StatusHelper;
use App\Http\Requests\JobCardRequest;
use App\Http\Requests\VehicleDefectsRequest;
use App\Models\configurations\Accessory;
use App\Models\configurations\GeneralTableConfiguration;
use App\Models\VehicleManagement\VehicleHeader;
use App\Models\WorkShopManagement\JobCardHeader;
use App\Models\WorkShopManagement\VehicleDefect;
use App\Models\WorkShopManagement\WorkShopComment;
use App\Models\WorkShopManagement\WorkShopVehicleAccessory;
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

        /// $receiverParts = explode('|', $request->get('service_advisor'));
        if ($request->has('job_card_number') && !empty($request->get('job_card_number'))) {
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

        $workshop_number = DocumentNumberGenerationService::generateReferenceNumber(Modules::WORKSHOP_DOCUMENT);
        $doc_number = DocumentNumberGenerationService::generateReferenceNumber(Modules::JOB_CARD);

        $section = GeneralTableConfiguration::where('name', '=', 'RECEPTION')
            ->where('type', ConfigurationTypes::WORK_SHOP_SECTION)
            ->first();

        if (empty($section)) {
            Log::info("Receiving Section Not Found");
        }

        $data = [
            'veh_reg' => $request->get('vehicle_registration'),
            'job_card_no' => $doc_number,
            'workshop_doc_no' => $workshop_number,
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
            'created_by' => $user->id
        ];

        $jobCardHeader = JobCardHeader::create($data);

        $this->moveVehicleToWorkShop($jobCardHeader->veh_reg);

        return $jobCardHeader;
    }

    public function getJobCardDetails(mixed $reference)
    {
        $query = DB::table('WM_JOB_CARD_HEADER')
            ->leftJoin('SEC_USERS', 'WM_JOB_CARD_HEADER.received_by', '=', 'SEC_USERS.staff_no')
            ->leftJoin('CONFIG_GENERAL_TABLES', 'WM_JOB_CARD_HEADER.receiving_section', '=', 'CONFIG_GENERAL_TABLES.code')
            ->where('CONFIG_GENERAL_TABLES.type', '=', ConfigurationTypes::WORK_SHOP_SECTION)
            ->where('WM_JOB_CARD_HEADER.job_card_no', '=', $reference)
            ->select('WM_JOB_CARD_HEADER.*', 'CONFIG_GENERAL_TABLES.name as section_in_name', 'SEC_USERS.name as service_advisor')
            ->get();

        return $query->first();
    }

    public function createJobCardAccessories(Request $request): void
    {
        DB::beginTransaction();
        $job_card_voucher = $request->get('job_card_voucher');
        $reference_number = $request->get('workshop_reference');
        $accessoryNames = Accessory::where('status', '=', StatusHelper::active())
            ->get();

        Log::info('Saving Accessories on ' . $reference_number);

        foreach ($accessoryNames as $accessoryName) {
            $accessoryCode = $accessoryName->code;
            $response = $request->get('field_' . trim($accessoryCode));
            $remarks = $request->get('comment_' . trim($accessoryCode));

            WorkShopVehicleAccessory::updateOrCreate(
                [
                    'job_card_no' => trim($job_card_voucher),
                    'workshop_reference' => trim($reference_number),
                    'code' => trim($accessoryCode),
                ],
                [
                    'name' => $accessoryName->name,
                    'remarks' => $remarks,
                    'is_present' => $response
                ]
            );
        }
        DB::commit();
    }

    public function createJobCardDefects(VehicleDefectsRequest $request): void
    {
        DB::beginTransaction();
        $models = [];

        foreach ($request->get('items') as $defect) {
            VehicleDefect::firstOrCreate(
                [
                    'workshop_reference' => $request['workshop_reference'],
                    'veh_sys' => $defect['vehicleSystem'],
                    //'job_card_no' => $request['job_card_no'],
                    'defect_category_code' => $defect['defectCategory'],
                    'defect_code' => $defect['defect'],
                ],
                [
                    'section_code' => $defect['workshopSection'],
                    'created_by' => auth()->user()->staff_no,
                    'date_def' => Carbon::parse($defect['date_def'])
                ]);
        }

        WorkShopComment::firstOrCreate(
            [
                'workshop_reference' => $request->workshop_reference,
                'type' => 'DEF',
            ],
            [
                'remarks' => $request->remarks ?? ' ',
                'status' => StatusHelper::new(),
                'created_by' => auth()->user()->staff_no
            ]);

        DB::commit();
    }

    public function getJobCardHeader(): Collection
    {
        return DB::table('WM_JOB_CARD_HEADER')
            ->leftJoin('SEC_USERS', 'WM_JOB_CARD_HEADER.received_by', '=', 'SEC_USERS.staff_no')
            ->leftJoin('CONFIG_GENERAL_TABLES', 'WM_JOB_CARD_HEADER.receiving_section', '=', 'CONFIG_GENERAL_TABLES.code')
            ->leftJoin('CONFIG_GENERAL_TABLES as config', 'WM_JOB_CARD_HEADER.repair_type', '=', 'config.code')
            ->leftJoin('CONFIG_WORKSHOP', 'WM_JOB_CARD_HEADER.receiving_section', '=', 'CONFIG_WORKSHOP.workshop_code')
            ->where('CONFIG_GENERAL_TABLES.type', '=', ConfigurationTypes::WORK_SHOP_SECTION)
            ->where('config.type', '=', ConfigurationTypes::REPAIR_TYPE)
            //->where('config.code', '=', )
            ->select('WM_JOB_CARD_HEADER.*',
                'CONFIG_WORKSHOP.workshop_name',
                'config.name as repair_type_name',
                'CONFIG_GENERAL_TABLES.name as section_in_name',
                'SEC_USERS.name as service_advisor')
            ->get();

    }

    public function getWorkShopPurchaseOfficeAndStore($workshop_code)
    {
        $data = DB::table('config_workshop')
            ->leftJoin('spms_stores_view', 'config_workshop.store_code', '=', 'spms_stores_view.code_store')
            ->leftJoin('zfm_purchase_offices', 'config_workshop.area_code', '=', 'zfm_purchase_offices.area')
            ->where('config_workshop.workshop_code', '=', $workshop_code)
            ->select('config_workshop.*',
                'spms_stores_view.code_store as store_code',
                'spms_stores_view.description as store_name',
                'zfm_purchase_offices.description as purchase_office',
                'zfm_purchase_offices.area as purchase_office_area',
                'zfm_purchase_offices.code_office as purchase_office_code')
            ->get();
        return $data->first();
    }

    private function moveVehicleToWorkShop($veh_reg)
    {
        VehicleHeader::where('registration_number', $veh_reg)
            ->update(['status' => StatusHelper::vehicleInWorkshop()]);
    }
}

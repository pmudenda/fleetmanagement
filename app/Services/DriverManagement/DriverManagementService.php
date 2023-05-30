<?php

namespace App\Services\DriverManagement;

use App\Helpers\StatusHelper;
use App\Http\Requests\DriverOnboardingRequest;
use App\Models\Driver;
use App\Models\Workflow\WorkflowModules;
use App\Services\FileUploads\FileUploadService;
use App\Services\Workflow\ReferenceNumberGeneratorService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DriverManagementService
{
    private FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function onboardDriver(DriverOnboardingRequest $request)
    {
        DB::beginTransaction();
        $user = Auth::user();

        $on_boarding_reference = ReferenceNumberGeneratorService::generateReferenceNumber(
            WorkflowModules::DRIVER_ONBOARDING
        );

        $model = Driver::create([
            'name' => $request->get("driver_name"),
            'staff_number' => $request->get("employee_number"),
            'grade' => $request->get("grade"),
            'position' => $request->get("job_title"),
            'location' => $request->get("location"),
            'is_designated_driver' => $request->get("isDesignatedDriver"),

            'license_number' => $request->get("license_number"),
            'license_date_issued' => Carbon::parse($request->get("license_date_issued")),
            'license_date_expiry' => Carbon::parse($request->get("license_date_expiry")),
            'license_category' => $request->get("license_class"),
            'on_boarding_reference' => $on_boarding_reference,
            'permit_number' => $request->get("permit_number"),

            'permit_date_issued' => Carbon::parse($request->get("permit_date_issued")),
            'permit_date_expiry' => Carbon::parse($request->get("permit_date_expiry")),

            'status' => StatusHelper::active(),
            'created_by' => $user->id,
        ]);


        $this->fileUploadService->uploadFile($request,
            'license_front_view',
            'DriverDocuments',
            $on_boarding_reference,
            'Driver Onboarding',
            'License Front View',
            $user
        );

        $this->fileUploadService->uploadFile($request,
            'license_back_view',
            'DriverDocuments',
            $on_boarding_reference,
            'Driver Onboarding',
            'License Back View',
            $user
        );

        $this->fileUploadService->uploadFile($request,
            'permit_copy',
            'DriverDocuments',
            $on_boarding_reference,
            'Driver Onboarding',
            'Permit',
            $user
        );

        DB::commit();

        return $model;
    }
}

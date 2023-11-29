<?php

namespace App\Services\DriverManagement;

use App\Constants\WorkflowModules;
use App\Exceptions\DriverOnBoardingException;
use App\Helpers\StatusHelper;
use App\Http\Requests\DriverOnboardingRequest;
use App\Models\Driver;
use App\Services\FileUploads\FileUploadService;
use App\Services\Workflow\DocumentNumberGenerationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DriverManagementService
{
    const DRIVER_ONBOARDING = 'Driver Onboarding';
    private FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function onboardDriver(DriverOnboardingRequest $request)
    {
        $driver = Driver::where('staff_number', $request->get("employee_number"))->first();

        if(!empty($driver)){
            throw new DriverOnBoardingException("Driver with Staff Number already exists");
        }

        DB::beginTransaction();
        $user = Auth::user();

        $onBoardingReference = DocumentNumberGenerationService::generateReferenceNumber(
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
            'on_boarding_reference' => $onBoardingReference,
            'permit_number' => $request->get("permit_number"),

            'permit_date_issued' => Carbon::parse($request->get("permit_date_issued")),
            'permit_date_expiry' => Carbon::parse($request->get("permit_date_expiry")),

            'status' => StatusHelper::active(),
            'created_by' => $user->id,
        ]);


        $this->fileUploadService->uploadFile($request,
            'license_front_view',
            'DriverDocuments',
            $onBoardingReference,
            self::DRIVER_ONBOARDING,
            'License Front View',
            $user
        );

        $this->fileUploadService->uploadFile($request,
            'license_back_view',
            'DriverDocuments',
            $onBoardingReference,
            self::DRIVER_ONBOARDING,
            'License Back View',
            $user
        );

        $this->fileUploadService->uploadFile($request,
            'permit_copy',
            'DriverDocuments',
            $onBoardingReference,
            self::DRIVER_ONBOARDING,
            'Permit',
            $user
        );

        DB::commit();

        return $model;
    }
}

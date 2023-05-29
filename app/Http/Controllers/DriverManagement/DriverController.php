<?php

namespace App\Http\Controllers\DriverManagement;

use App\Constants\ErrorMessages;
use App\Enums\ConfigurationTypes;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\DriverOnboardingRequest;
use App\Models\configurations\GeneralTableConfigurations;
use App\Models\Driver;
use App\Models\Security\Role;
use App\Models\Security\User;
use App\Models\Workflow\WorkflowModules;
use App\Services\FileUploads\FileUploadService;
use App\Services\Workflow\ReferenceNumberGeneratorService;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class DriverController extends Controller
{
    private FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function create(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $licenseClasses = GeneralTableConfigurations::where('type', '=', ConfigurationTypes::LICENSE_CLASS->value)
            ->get();

        return view('modules.driverManagement.create')
            ->with(compact('licenseClasses'));
    }

    public function store(DriverOnboardingRequest $request): JsonResponse
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

        return response()->json([
            'success' => !empty($model),
            'payload' => $model,
            'message' => 'Driver Onboarded Successfully'
        ]);
    }

    public function show(Driver $user): Factory|View|Application
    {
        //$roles = Role::all();
        return view('modules.driverManagement.show')
            ->with(compact('user'));
    }

    public function driverList(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $users = Driver::get();
        return view('modules.driverManagement.driverList')->with(compact('users'));
    }

    public function findDriver(Request $request): JsonResponse
    {
        $searchParam = strtoupper(trim($request->searchCriteria));
        $drivers = Driver::where('staff_number', '=', $searchParam)
            ->orWhere('name', 'LIKE', "%{$searchParam}%")
            ->get();

        if (empty($drivers)) {
            return response()->json([
                'success' => 'false',
                'payload' => [],
                'message' => ErrorMessages::driverNotFound
            ]);
        }

        return response()->json([
            'success' => true,
            'payload' => $drivers
        ]);

    }
}

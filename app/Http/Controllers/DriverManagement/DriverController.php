<?php

namespace App\Http\Controllers\DriverManagement;

use App\Constants\ErrorMessages;
use App\Enums\ConfigurationTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\DriverOnboardingRequest;
use App\Models\configurations\GeneralTableConfigurations;
use App\Models\Driver;
use App\Services\DriverManagement\DriverManagementService;
use App\Services\FileUploads\FileUploadService;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class DriverController extends Controller
{
    private FileUploadService $fileUploadService;
    private DriverManagementService $driverManagementService;

    public function __construct(FileUploadService $fileUploadService, DriverManagementService $driverManagementService)
    {
        $this->fileUploadService = $fileUploadService;
        $this->driverManagementService = $driverManagementService;
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
        Log::info('Posting Driver Data');

        try {
            $model = $this->driverManagementService->onboardDriver($request);
            return response()->json([
                'success' => !empty($model),
                'payload' => $model,
                'redirectUrl' => URL::signedRoute('driver.list'),
                'message' => 'Driver Onboarded Successfully'
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'description' => $e,
                'payload' => [],
                'message' => ErrorMessages::getMessage('err_0005')
            ]);
        }
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

        $driver = Driver::where('staff_number', '=', $searchParam)
            ->orWhere('name', 'LIKE', "%{$searchParam}%")
            ->first();

        if (empty($driver)) {
            return response()->json([
                'success' => 'false',
                'payload' => [],
                'message' => str_replace('@input', $searchParam, ErrorMessages::getMessage('err_0011'))
            ]);
        }

        $nowDate = Carbon::now();

        if ($nowDate->gt($driver->license_date_expiry)) {
            return response()->json([
                'success' => 'false',
                'payload' => [],
                'message' => str_replace('@input',
                    $searchParam,
                    ErrorMessages::getMessage('err_0010')
                )
            ]);
        }

        if ($nowDate->gt($driver->permit_date_expiry)) {
            return response()->json([
                'success' => 'false',
                'payload' => [],
                'message' => str_replace('@input',
                    $searchParam,
                    ErrorMessages::getMessage('err_0009')
                )
            ]);
        }

        return response()->json([
            'success' => true,
            'payload' => $driver
        ]);

    }
}

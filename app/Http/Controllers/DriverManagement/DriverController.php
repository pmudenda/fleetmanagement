<?php

namespace App\Http\Controllers\DriverManagement;

use App\Constants\ErrorMessages;
use App\Enums\ConfigurationTypes;
use App\Exceptions\DriverSearchException;
use App\Http\Controllers\Controller;
use App\Http\Requests\DriverOnboardingRequest;
use App\Models\Driver;
use App\Models\Settings\GeneralTable;
use App\Services\DriverManagement\DriverManagementService;
use App\Services\FileUploads\FileUploadService;
use App\Services\Security\UserService;
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
    const INPUT = '@input';
    private FileUploadService $fileUploadService;
    private DriverManagementService $driverManagementService;
    private UserService $userService;

    public function __construct(FileUploadService       $fileUploadService,
                                DriverManagementService $driverManagementService,
                                UserService             $userService)
    {
        $this->fileUploadService = $fileUploadService;
        $this->driverManagementService = $driverManagementService;
        $this->userService = $userService;
    }

    public function create(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $licenseClasses = GeneralTable::where('type', '=', ConfigurationTypes::LICENSE_CLASS->value)
            ->get();

        return view('modules.driverManagement.create')
            ->with(compact('licenseClasses'));
    }

    public function store(DriverOnboardingRequest $request): JsonResponse
    {
        Log::debug('Posting Driver Data');

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
        return view('modules.driverManagement.show')
            ->with(compact('user'));
    }

    public function driverList(): View
    {
        $users = Driver::get();
        return view('modules.driverManagement.driverList')
            ->with(compact('users'));
    }

    public function findDriver(Request $request): JsonResponse
    {
        try {
            $searchParam = strtoupper(trim($request->searchCriteria));

            $useDriverModule = config('systeminfo.enableDriverModule');

            Log::debug("searching " . $searchParam);

            if ($useDriverModule) {
                return $this->getAuthorisedDriver($searchParam);
            }

            if (str_starts_with($searchParam, 'C7') || str_starts_with($searchParam, '7')) {
                $driver = $this->userService->searchEmployee($searchParam);
            } else {
                $driver = $this->userService->searchEmployee($searchParam)->first();
            }

            if (empty($driver)) {
                throw new DriverSearchException(
                    str_replace(
                        self::INPUT,
                        $searchParam,
                        ErrorMessages::getMessage('err_0011'))
                );
            }

            if ($driver->con_st_code != 'ACT' && $driver->con_st_code != '01') {
                throw new DriverSearchException(
                    str_replace(self::INPUT,
                    $searchParam,
                    ErrorMessages::getMessage('err_0027')
                ));
            }

            return response()->json([
                'success' => true,
                'payload' => $driver
            ]);

        } catch (\Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_0005');
            if ($e instanceof DriverSearchException) {
                $message = $e->getMessage();
            }

            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }
    }

    /**
     * @throws DriverSearchException
     */
    private function getAuthorisedDriver(string $searchParam): JsonResponse
    {
        $driver = Driver::where('staff_number', '=', $searchParam)
            ->orWhere('name', 'LIKE', "%{$searchParam}%")
            ->first();

        if (empty($driver)) {
            throw new DriverSearchException(
                str_replace(
                    self::INPUT,
                    $searchParam,
                    ErrorMessages::getMessage('err_0011'))
            );

        }

        $nowDate = Carbon::now();

        if ($nowDate->gt($driver->license_date_expiry)) {
            throw new DriverSearchException(
                str_replace(self::INPUT,
                    $searchParam,
                    ErrorMessages::getMessage('err_0010')
                )
            );
        }

        if ($nowDate->gt($driver->permit_date_expiry)) {
            throw new DriverSearchException(
                str_replace(self::INPUT,
                    $searchParam,
                    ErrorMessages::getMessage('err_0009')
                ));
        }

        return response()->json([
            'success' => true,
            'payload' => $driver
        ]);
    }
}

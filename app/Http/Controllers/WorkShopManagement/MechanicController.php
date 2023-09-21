<?php

namespace App\Http\Controllers\WorkShopManagement;

use App\Constants\SystemMessages;
use App\Enums\ConfigurationTypes;
use App\Exceptions\UserOnBoardingException;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\MechanicOnboarding;
use App\Http\Requests\UserSync;
use App\Models\Reference\PHCMSEmployee;
use App\Models\Security\Role;
use App\Models\Settings\GeneralTable;
use App\Models\Settings\WorkShop;
use App\Models\WorkShopManagement\Mechanic;
use App\Services\Logging\ActivityLogsService;
use App\Services\Organization\StructureService;
use App\Services\WorkShopManagement\MechanicsService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MechanicController extends Controller
{

    private MechanicsService $mechanicsService;

    public function __construct(MechanicsService $mechanicsService)
    {
        $this->mechanicsService = $mechanicsService;
    }

    public function create(): View
    {
        $role = Role::where('name', '=', 'MECHANIC')->first();
        $businessUnits = (new StructureService)->getBusinessUnits();
        $costCenters = (new StructureService)->getCostCenters();

        $workshopList = WorkShop::get();

        $workshopSectionList = GeneralTable::where('type', '=',
            ConfigurationTypes::WORK_SHOP_SECTION)->get();

        return view('modules.mechanicManagement.create')
            ->with(compact(
                'role',
                'businessUnits',
                'costCenters',
                'workshopList',
                'workshopSectionList'));
    }

    public function store(MechanicOnboarding $request): JsonResponse
    {
        try {
            $this->mechanicsService->createMechanic($request);
            return response()->json([
                'success' => true,
                'message' => SystemMessages::MECHANIC_ONBOARDED
            ]);

        } catch (\Exception $ex) {
            Log::error($ex);
            $message = "User Failed to be created because of an error";
            if ($ex instanceof UserOnBoardingException) {
                $message = $ex->getMessage();
            }

            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }

    }

    public function list(): View
    {
        $mechanics = DB::table('wm_mechanics mec')
            ->leftJoin('config_workshop wkshp',
                'mec.workshop_code',
                '=',
                'wkshp.workshop_code')
            ->leftJoin('sec_users usr',
                'mec.staff_no',
                '=',
                'usr.staff_no')
            ->leftJoin('config_general_tables wkshp_sec', function ($join) {
                $join->on('mec.section_code', '=', 'wkshp_sec.code')
                    ->where('wkshp_sec.type', '=', 'WORK_SHOP_SEC');
            })
            ->select(
                'mec.*',
                'mec.id as mechanic_id',
                'usr.*',
                'wkshp_sec.name as wkshp_section_name',
                'wkshp.workshop_name'
            )->get();
        return view('modules.mechanicManagement.list')
            ->with(compact('mechanics'));
    }

    public function find(Request $request): JsonResponse
    {
        try {
            Log::info('Searching for mechanic ' . $request->get('staff_no'));

            $mechanic = Mechanic::where('staff_no', '=', $request->get('staff_no'))
                ->where('status', '=', StatusHelper::active())
                ->first();

            if (empty($mechanic)) {
                return response()->json([
                    'state' => 'failure',
                    'payload' => []
                ]);
            }

            $employee = PHCMSEmployee::where('con_per_no', '=', $mechanic->staff_no)
                ->where('con_st_code', '=', 'ACT')
                ->first();

            return response()->json([
                'state' => 'success',
                'payload' => [
                    'employee' => $employee,
                    'mechanic' => $mechanic
                ]
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'payload' => []
            ]);
        }
    }

    public function show(Request $request): View
    {
        $employee = config("tables.table_names.employee");
        $staffId = $request->get('ref');

        $workshopList = WorkShop::get();

        $workshopSectionList = GeneralTable::where('type', '=',
            ConfigurationTypes::WORK_SHOP_SECTION)->get();

        $mechanic = DB::table('wm_mechanics mec')
            ->leftJoin('config_workshop wkshp',
                'mec.workshop_code',
                '=',
                'wkshp.workshop_code')
            ->leftJoin('config_general_tables wkshp_sec', function ($join) {
                $join->on('mec.section_code',
                    '=',
                    'wkshp_sec.code')
                    ->where('wkshp_sec.type',
                        '=',
                        ConfigurationTypes::WORK_SHOP_SECTION
                    );
            })
            //->leftJoin("$employee emp", "mec.staff_no", "=", 'emp.alt_per_no')
            ->where('mec.id', '=', $staffId)
            ->select(
                "mec.*",
                'wkshp_sec.name as wkshp_section_name',
                'wkshp.workshop_name'
            )->first();

        return view('modules.mechanicManagement.show')
            ->with(compact('mechanic',
                'workshopList',
                'workshopSectionList'));
    }

    public function update(Request $request): JsonResponse
    {
        try {
            $this->mechanicsService->updateDetails($request);
            ActivityLogsService::store($request, 'Updating of User', 'update', ' user updated');
            return response()->json([
                'state' => 'success',
                'message' => SystemMessages::userUpdateSuccessful()
            ]);
        } catch (Exception $e) {
            $message = SystemMessages::userUpdateFailed();
            Log::info($message);
            Log::error($e);
            return response()->json([
                'state' => 'error',
                'error' => $message
            ]);
        }
    }

    public function sync(UserSync $request): JsonResponse
    {
        try {
            Log::info('Start Sync Mechanic Data Update: User Id ' . $request->userId);
            $this->mechanicsService->syncMechanicFullDetails($request->userId);
            return response()->json([
                'state' => 'success',
                'message' => SystemMessages::userUpdateSuccessful()
            ]);
        } catch (Exception $e) {
            $message = SystemMessages::userUpdateFailed();
            Log::info($message);
            Log::error($e);
            return response()->json([
                'state' => 'error',
                'error' => $message
            ]);
        }
    }
}

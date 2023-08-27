<?php

namespace App\Http\Controllers\WorkshopManagement;

use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Models\Reference\PHCMSEmployee;
use App\Models\WorkShopManagement\Mechanic;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MechanicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function list(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $mechanics = DB::table('wm_mechanics mec')
            ->leftJoin('config_workshop wkshp',
                'mec.workshop_code',
                '=',
                'wkshp.workshop_code')
            ->leftJoin('config_general_tables wkshp_sec', function ($join) {
                $join->on('wkshp_sec', 'mec.sec_code', '=', 'wkshp.workshop_code')
                    ->where('wkshp_sec.type', '=', 'WORK_SHOP_SEC');
            })
            ->select(
                'mec.*',
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
}

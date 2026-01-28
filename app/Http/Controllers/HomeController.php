<?php

namespace App\Http\Controllers;

use App\Helpers\StatusHelper;
use App\Models\Driver;
use App\Models\Security\User;
use App\Models\WorkShopManagement\Mechanic;
use App\Services\VehicleManagement\VehicleDetailsService;
use App\Services\Workflow\WorkflowService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    private WorkflowService $workflowService;
    private VehicleDetailsService $vehicleDetailsService;

    public function __construct(
        WorkflowService $workflowService,
        VehicleDetailsService $vehicleDetailsService
    ) {
        $this->workflowService = $workflowService;
        $this->vehicleDetailsService = $vehicleDetailsService;
    }

    public function logout(): Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $user = auth()->user();
        Log::debug("Logging Off The System" . $user->staff_no);

        session()->pull('simulating', false);
        session()->forget('simulating');

        Auth::logout();

        return redirect('/login')->with(['msg_body' => 'Signing out!']);
    }

    public function dashboard(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $user = auth()->user();
        $approvalTasks = $this->workflowService->getMyApprovalTasks($user);

        // statuses you fetch
        $vehicleData = $this->vehicleDetailsService->getAllVehiclesByStatus(['01', '02', '04', '05', '09']);

        $mechanics = Mechanic::whereRelation('user', 'CON_ST_CODE', '01')->count();
        $activeUsers = User::where('con_st_code', StatusHelper::active())->count();
        $activeDrivers = Driver::whereRelation('user', 'CON_ST_CODE', '01')->count();

        return view('dashboard.home')->with(compact(
            'approvalTasks',
            'vehicleData',
            'mechanics',
            'activeUsers',
            'activeDrivers'
        ));
    }

    public function gatePass(Request $request): View|RedirectResponse
    {
        if (!$request->has('ref')) {
            return redirect(route('home'));
        }

        $vehicle = $this->vehicleDetailsService->getVehicleByReg($request->get('ref'));

        return view('dashboard.pass')->with(compact('vehicle'));
    }
}

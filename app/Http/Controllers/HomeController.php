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
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    private WorkflowService $workflowService;

    public function __construct(WorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    public function logout(): Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $user = auth()->user();
        Log::info("Logging Off The System" . $user->staff_no);
        session()->pull('simulating', false);
        Auth::logout();
        return redirect('/login')
            ->with(['msg_body' => 'Signing out!']);
    }

    public function dashboard(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $user = auth()->user();
        $approvalTasks = $this->workflowService->getMyApprovalTasks($user->staff_no);
        $vehicleData = (new VehicleDetailsService)->getAllVehiclesByStatus(['01', '02', '04', '05', '09']);
        $mechanics = Mechanic::get()->count();
        $activeUsers = User::where('con_st_code', '=', StatusHelper::active())->count();
        $activeDrivers = Driver::get()->count();
        return view('dashboard.home')
            ->with(compact('approvalTasks',
                'vehicleData', 'mechanics', 'activeUsers', 'activeDrivers'));
    }
}

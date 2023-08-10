<?php

namespace App\Http\Controllers;

use App\Models\DataCleanUp;
use App\Models\Reference\TMSDataCleanUp;
use App\Services\VehicleManagement\VehicleDetailsService;
use App\Services\Workflow\WorkflowService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private WorkflowService $workflowService;

    public function __construct(WorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    public function logout(): Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        auth()->user();
        //$user->has_active_session = ConfigHelper::currentLoginFalse();
        //$user->save();
        Auth::logout();
        return redirect('/login')->with(['msg_body' => 'Signing out!']);
    }

    public function dashboard(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $user = auth()->user();

        $approvalTasks = $this->workflowService->getMyApprovalTasks($user->staff_no);
        $vehicleData = VehicleDetailsService::getAllVehicles();
        return view('dashboard.home')
            ->with(compact('approvalTasks',
                'vehicleData'));
    }
}

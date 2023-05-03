<?php

namespace App\Http\Controllers\Workflow;

use App\Http\Controllers\Controller;
use App\Models\Workflow\WorkflowTask;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class WorkflowController extends Controller
{
    public function showWorkTask(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $workTask = WorkflowTask:: orderBy('created_at', 'ASC')->get();
        return view('software-incidence.approvals', compact(['workTask']))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function approve(Request $request): JsonResponse
    {
        return response()->json([
            'requestPayload' => $request->json(),
            'success' => true,
            'redirectUrl'=> route('list.fuel.requisition'),
            'message' => 'Request Approved Successfully'
        ]);
    }
}

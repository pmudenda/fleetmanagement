<?php

namespace App\Http\Controllers;

use App\Services\Projects\ProjectCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    private ProjectCodeService $projectCodeService;

    public function __construct(ProjectCodeService $projectCodeService)
    {
        $this->projectCodeService = $projectCodeService;
    }

    public function findProjectByCode(Request $request): JsonResponse
    {
        $period = date('M-Y');

        $searchCriteria = strtoupper(trim($request->input('search')));

        $activeProjects = $this->projectCodeService->getActiveProjects($period, $searchCriteria);

        return response()->json(array(
            'items' => $activeProjects,
            'total_count' => $activeProjects->count()
        ));
    }
}

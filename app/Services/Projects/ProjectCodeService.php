<?php

namespace App\Services\Projects;

use App\Models\reference\ActiveProjectsModel;
use Illuminate\Support\Facades\DB;

class ProjectCodeService
{
    public function getActiveProjects(string $period, string $searchCriteria)
    {
         $query = "SELECT * FROM SPMS_PROJECTS_VIEW
          WHERE  PROJECT_CODE = '" . $searchCriteria . "' AND PERIOD LIKE '%{$period}%' AND BEGINNING_BALANCE != 0";
        $activeProjects = DB::select($query);

        return ActiveProjectsModel::hydrate($activeProjects);
    }
}

<?php

namespace App\Services\Projects;

use App\Models\Reference\ActiveProjects;
use Illuminate\Support\Facades\DB;

class ProjectCodeService
{
    public function getActiveProjects(string $searchCriteria)
    {
        $projects = config('tables.table_names.projects');
         $query = "SELECT * FROM $projects
          WHERE  (CODE_PROJECT LIKE '%{$searchCriteria}%'
                      OR DESCRIPTION LIKE '%{$searchCriteria}%') AND STATUS = '01'";
        $activeProjects = DB::select($query);

        return ActiveProjects::hydrate($activeProjects);
    }
}

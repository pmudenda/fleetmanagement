<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowStep extends Model
{
    use HasFactory;
    protected $table = 'WFL_WORKFLOW_STEP';
    protected $fillable = [
        'Process_Id',
        'Step_Id',
        'Name',
        'IsInitial_Step',
        'Is_Final_Step',
        'Previous_Step',
        'Next_Step',
        'Next_Process',
        'Action_Page',
        'Created_By',
        'Modified_By',
        'Privilege'
    ];
}

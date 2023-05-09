<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowLog extends Model
{
    use HasFactory;

    protected $table = 'WFL_WORKFLOW_LOGS';
    protected $fillable = [
        'Reference',
        'Step_Id',
        'Actioning_Officer',
        'Action',
        'Status',
        'Action_Date',
        'Next_Step',
        'Previous_Step',
        'Remarks'
    ];
}

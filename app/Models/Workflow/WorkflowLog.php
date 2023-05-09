<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowLog extends Model
{
    use HasFactory;

    protected $table = 'WFL_WORK_FLOW_LOGS';
    protected $fillable = [
        'Reference',
        'StepId',
        'ActioningOfficer',
        'Action',
        'Status',
        'ActionDate',
        'NextStep',
        'PreviousStep',
        'Remarks'
    ];
}

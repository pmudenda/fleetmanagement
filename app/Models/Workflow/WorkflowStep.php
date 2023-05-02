<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowStep extends Model
{
    use HasFactory;
    protected $table = 'workflowstep';
    protected $fillable = [
        'ProcessId',
        'StepId',
        'Name',
        'IsInitialStep',
        'IsFinalStep',
        'PreviousStep',
        'NextStep',
        'NextProcess',
        'ActionPage',
        'CreatedBy',
        'CreatedDate',
        'ModifiedBy',
        'ModifiedDate',
        'PrivilegeId'
    ];
}

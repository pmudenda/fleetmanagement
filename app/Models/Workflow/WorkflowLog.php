<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowLog extends Model
{
    protected $table = 'WFL_WORKFLOW_LOGS';
    protected $fillable = [
        'reference',
        'step_id',
        'actioning_officer',
        'action',
        'activity',
        'status',
        'action_date',
        'next_step',
        'previous_step',
        'remarks'
    ];
}

<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowTaskDetail extends Model
{
    use HasFactory;
    protected $table = "WFL_WORKFLOW_TASK_DETAILS";

    protected $fillable = [
        'reference',
        'process_code',
        'user_id',
        'current_step_id',
        'actioning_officer',
        'status',
        'date_started',
        'date_ended'
    ];
}

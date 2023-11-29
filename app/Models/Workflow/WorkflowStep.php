<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowStep extends Model
{
    use HasFactory;
    protected $table = 'WFL_WORKFLOW_STEP';
    protected $fillable = [
        'process_id',
        'step_id',
        'name',
        'is_initial_step',
        'is_final_step',
        'previous_step',
        'next_step',
        'next_process',
        'action_page',
        'created_by',
        'modified_by',
        'privilege'
    ];
}

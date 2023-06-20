<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowApprovalLimit extends Model
{
    use HasFactory;
    protected $table = "WFL_WORKFLOW_APPROVAL_LIMIT";

    protected $fillable = [
        'user_unit_code',
        'user_unit_name',
        'office',
        'final_step',
        'approval_limit'
    ];
}

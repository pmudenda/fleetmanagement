<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowProcess extends Model
{
    use HasFactory;

    protected $table = 'WFL_WORKFLOW_PROCESSES';

    protected $fillable = [
        'process_code',
        'name',
        'description',
        'created_by',
        'modified_by',
    ];
}

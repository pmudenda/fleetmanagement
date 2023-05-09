<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowTask extends Model
{
    use HasFactory;

    protected $table = "WFL_WORKFLOW_TASK";
    protected $fillable = [
        'message',
        'status',
        'date_acted',
        'subject',
        'assigned_user',
        'sender',
        'url',
        'reference',
        'priority',
        'description',
        'created_by',
        'modified_by',
    ];
}

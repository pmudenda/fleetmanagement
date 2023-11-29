<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowTaskHeader extends Model
{
    use HasFactory;

    protected $table = "WFL_WORKFLOW_TASK";
    protected $fillable = [
        'status',
        'subject',
        'assigned_user',
        'long_description',
        'url',
        'date_acted',
        'reference',
        'priority',
        'description',
        'created_by',
        'modified_by',
        'process_code',
        'amount',
        'user_unit'
    ];
}

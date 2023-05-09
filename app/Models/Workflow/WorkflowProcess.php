<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowProcess extends Model
{
    use HasFactory;

    protected $table = 'workflowprocess';

    protected $fillable = [
        'ProcessCode',
        'Name',
        'Description',
        'CreatedBy',
        'CreatedDate',
        'ModifiedBy',
        'ModifiedDate'
    ];
}

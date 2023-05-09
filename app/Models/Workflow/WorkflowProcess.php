<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowProcess extends Model
{
    use HasFactory;

    protected $table = 'WFL_WORKFLOW_PROCESSES';

    protected $fillable = [
        'Process_Code',
        'Name',
        'Description',
        'Created_By',
        //'Created_Date',
        'Modified_By',
        //'Modified_Date'
    ];
}

<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowLog extends Model
{
    use HasFactory;
    protected $table= 'workflowlog';
    protected $fillable = [


	'Reference',
	'StepId',
	'ActioningOfficer',
	'Action',
	'Status',
	'ActionDate',
	'NextStep',
	'PreviousStep',
	 'Remarks'


    ];
}

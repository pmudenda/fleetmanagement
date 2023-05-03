<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowTaskDetail extends Model
{
    use HasFactory;
    protected $table = "workflowtaskdetail";

    protected $fillable = [
        'Reference',
        'ProcessCode',
        'UserId',
        'CurrentStepId',
        'ActioningOfficer',
        'Status',
        'DateStarted',
        'DateEnded'
    ];
}

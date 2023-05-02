<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowTask extends Model
{
    use HasFactory;

    protected $table = "workflowtask";
    protected $fillable = [
        'Message',
        'Status',
        'DateReceived',
        'DateRead',
        'Subject',
        'AssignedUser',
        'Sender',
        'url',
        'reference',
        'priority',
        'description',
    ];
}

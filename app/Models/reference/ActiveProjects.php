<?php

namespace App\Models\reference;

use App\Models\Main\ConfigWorkFlow;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveProjects extends Model
{
    use HasFactory;

    protected $table = 'SPMS_PROJECTS_VIEW';

    protected $fillable = [
        'period',
        'description',
        'project_code',
    ];

}

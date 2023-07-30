<?php

namespace App\Models\reference;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveProjects extends Model
{
    protected $table = 'ZFM_SPMS_PROJECTS_VIEW';

    protected $attributes = [
        'period',
        'description',
        'project_code',
    ];

}

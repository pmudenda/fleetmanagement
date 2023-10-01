<?php

namespace App\Models\Reference;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveProjects extends Model
{
    protected $table = 'ZFM_PROJECTS_VIEW';

    protected $attributes = [
        'period',
        'description',
        'project_code',
    ];

}

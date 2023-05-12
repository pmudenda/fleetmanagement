<?php

namespace App\Models\reference;

use App\Models\Main\ConfigWorkFlow;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveProjectsModel extends Model
{
    use HasFactory;

    //table name
    protected $table = 'active_projects_view';

    protected $fillable = [
        'period',
        'description',
        'project_code',
    ];

}

<?php

namespace App\Models\WorkShopManagement;

use Illuminate\Database\Eloquent\Model;

class AssessmentObservation extends Model
{
    protected $table = '';
    protected $fillable = [
        'reference',
        'image_path',
        'remarks',
        'reported_by',
        'modified_by',
    ];
}

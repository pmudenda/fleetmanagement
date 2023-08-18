<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model
{
    protected $table = '';

    protected $fillable = [
        'event_date',
        'referenceNumber',
        'justification',
        'event',
        'subject',
        'user_id',
        'name',
        'field_action',
        'new_value',
        'old_value',
    ];
}

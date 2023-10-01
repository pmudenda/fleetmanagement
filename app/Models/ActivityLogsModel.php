<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLogsModel extends Model
{
    protected $table = 'SEC_AUDIT_LOG';

    protected $primaryKey = 'id';

    protected $fillable = [

        'user_id',
        'staff_no',
        'username',
        'user_email',

        'ip_address',
        'route_url',
        'previous_url',
        'request_method',
        'request_params',

        'action_name',
        'action_type',
        'comment',
        'meta_data',

        'device',
        'device_type',
        'os',
        'os_version',
        'browser',
        'browser_version',

        'iso_code',
        'country',
        'city',
        'state',
        'state_name',
        'postal_code',
        'latitude',
        'longitude',
        'timezone',
        'continent',
        'currency',
        'value',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

}

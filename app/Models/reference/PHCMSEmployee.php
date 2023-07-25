<?php

namespace App\Models\reference;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PHCMSEmployee extends  Model
{
    use HasFactory;
    protected $connection = 'oracle_isd';
    protected $table = 'ipa_phris_view';
    /*protected $primaryKey = 'con_per_no';*/
    /*protected $fillable = [
        'contract_type',
        'con_st_code',
        'con_wef_date',
        'con_wet_date',
        'name',
        'nrc',
        'sex',
        'mobile_no',
        'group_type',
        'job_title',
        'grade',
        'functional_section',
        'directorate',
        'service-desk',
        'pay_point',
        'bu_code',
        'cc_code',
        'staff_email',
        'job_code',
        'station',
        'affiliated_union'
    ] ;*/

}

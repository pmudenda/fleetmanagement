<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ETollCard extends Model
{
    protected $table = 'CM_ETOLL_CARDS';

    protected $fillable = [
        'batchNumber' ,
        'cardScheme' ,
        'cardNumber',
        'cardStatus' ,
        'dateIssued',
        'expiryDate' ,
        'cvv' ,
        'contactNumber' ,
        'assignedTo' ,
        'responseHead' ,
        'responseHeadId' ,
        'comments',
        'assigned_distributor',
        'veh_reg'
    ];
}

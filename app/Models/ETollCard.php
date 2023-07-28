<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ETollCard extends Model
{
    use HasFactory;

    protected $table = '';

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
        'comments'
    ];
}

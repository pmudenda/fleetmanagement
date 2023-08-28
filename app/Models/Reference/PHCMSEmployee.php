<?php

namespace App\Models\Reference;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PHCMSEmployee extends  Model
{
    use HasFactory;
    /*protected $connection = 'oracle_isd';*/
    protected $table = 'ipa_phris_view';
}

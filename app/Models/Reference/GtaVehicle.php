<?php

namespace App\Models\Reference;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GtaVehicle extends Model
{
    use HasFactory;

    protected $table = 'GTAVEHIC_VIEW';

    protected $fillable = [
        'user_act',
        'fech_act',
        'hora_act',
        'matricula',
        'responsible',
        'unidad_ads',
        'unidad_ces',
        'vehiculo',
        'bastidor',
        'fech_matr',
        'asistencia',
        'permiso',
        'tar_trans',
        'serie',
        'fech_sol_tt',
        'estado',
        'num_cert',
        'alquiler',
        'km_rr',
        'fech_parte',
        'prox_rev',
        'km_prev',
        'tara',
        'pmr_sf_cf',
        'marca_neum',
        'num_asientos',
        'marca_motor',
        'tipo_motor'
    ];
}

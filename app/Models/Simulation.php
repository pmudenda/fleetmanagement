<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Simulation extends Model
{
    protected $table = 'gen_simulations';
    protected $fillable = [
        "created_at",
        "simulator",
        "simulated",
        "simulate_start",
        "simulate_end",
        "comments",
    ];
}

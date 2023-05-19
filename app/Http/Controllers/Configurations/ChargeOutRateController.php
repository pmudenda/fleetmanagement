<?php

namespace App\Http\Controllers\configurations;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ChargeOutRateController extends Controller
{

    public function index(): \Illuminate\Contracts\View\View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('configurations.chargeoutrate');
    }

    public function store(Request $request){

    }
}

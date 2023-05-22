<?php

namespace App\Http\Controllers\DriverManagement;

use App\Enums\ConfigurationTypes;
use App\Http\Controllers\Controller;
use App\Models\configurations\GeneralTableConfigurations;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class DriverController extends Controller
{

    public function index(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $licenseClasses = GeneralTableConfigurations::where('type', '=', ConfigurationTypes::LICENSE_CLASS->value)->get();
        return view('modules.driverManagement.index')->with(compact('licenseClasses'));
    }

    public function driverList(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $users = []; //User::select('*')->get();
        return view('modules.driverManagement.driverList')->with(compact('users'));
    }

    public function findDriver()
    {

    }
}

<?php

namespace App\Http\Controllers\DriverManagement;

use App\Constants\ErrorMessages;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Models\general\BusinessUnits;
use App\Models\general\CostCenters;
use App\Models\reference\PHCMSEmployee;
use App\Models\Security\Role;
use App\Models\Security\User;
use App\Services\Security\ParameterEncryption;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DriverController extends Controller
{

    public function index()
    {
        $users = []; //User::select('*')->get();
        return view('DriverManagement.index')->with(compact('users'));
    }    
    
    public function driverList()
    {
        $users = []; //User::select('*')->get();
        return view('DriverManagement.driverList')->with(compact('users'));
    }
}
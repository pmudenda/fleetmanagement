<?php

namespace App\Http\Controllers\WorkshopManagement;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class WorkshopController extends Controller
{
    public function index(): View
    {
        return view('modules.workshopManagement.index');
    }

}

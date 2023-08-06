<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class RemindersController extends Controller
{
    public function index(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('modules.reminders.create');
    }

    public function create(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('modules.reminders.create');
    }
}

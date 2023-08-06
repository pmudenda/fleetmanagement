<?php

namespace App\Http\Controllers;

use App\Http\Requests\RenewalReminder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

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

    public function store(RenewalReminder $reminder): JsonResponse
    {
        return response()->json([
            'state' => 'success',
            'payload' => $reminder->all()
        ]);
    }
}

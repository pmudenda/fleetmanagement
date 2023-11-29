<?php

namespace App\Http\Controllers;

use App\Http\Requests\RenewalReminder;
use App\Http\Requests\ServiceReminder;
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

    public function createRenewalReminder(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('modules.reminders.create');
    }

    public function createServiceReminder(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('modules.reminders.create');
    }

    public function storeRenewalReminder(RenewalReminder $reminder): JsonResponse
    {
        return response()->json([
            'state' => 'success',
            'payload' => $reminder->all()
        ]);
    }

    public function storeServiceReminder(ServiceReminder $reminder): JsonResponse
    {
        return response()->json([
            'state' => 'success',
            'payload' => $reminder->all()
        ]);
    }
}

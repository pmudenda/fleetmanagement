<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function create(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $user = Auth::user();
        $details = [];
        $materials = [];
        $materialsHeader = null;
        $services = collect([]);
        $view_name = 'modules.workshopManagement.booking.create';

        return view($view_name)
            ->with(compact(
                'details',
                'materials',
                'materialsHeader',
                'services',
                'user'
            ));

    }

    public function list()
    {
        return "Requisitions here";
    }
}

<?php

namespace App\Http\Controllers\WorkShopManagement;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function create(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $user = Auth::user();
        $details = [];
        $materials = [];
        $materialsHeader = null;
        $services = collect([]);
        return view('modules.workshopManagement.booking.create')
            ->with(compact(
                'details',
                'materials',
                'materialsHeader',
                'services',
                'user'
            ));

    }

    /*public function list()
    {
        return "Requisitions here";
    }*/
}

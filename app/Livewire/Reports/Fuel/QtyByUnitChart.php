<?php

namespace App\Livewire\Reports\Fuel;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class QtyByUnitChart extends Component
{
    public $month, $year;

    public function render()
    {
        $filter = sprintf("%d%02d", $this->year, $this->month);
        $data = Cache::rememberForever("fuel-cost-by-unit-{$filter}",function () use ($filter) {
            return DB::table('FUEL_BY_UNIT_VIEW')
                ->selectRaw('unit as name, SUM(qty) as total')
                ->where('month',$filter)
                ->groupBy(['unit','fuel_type'])
                ->orderBy('total', 'DESC')
                ->take(10)
                ->get()
                ->groupBy('name');
        });
//        dd($filter);

        $categories = $data->keys()->toArray();

        $data = $data->map(function ($app){
            return (double)$app->sum('total');
        })->values()->toArray();
        return view('livewire.reports.fuel.qty-by-unit-chart',compact('data','categories'));
    }
}

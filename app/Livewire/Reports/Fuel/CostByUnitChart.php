<?php

namespace App\Livewire\Reports\Fuel;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CostByUnitChart extends Component
{
    public $month = '202309';
    public function render()
    {
        $data = Cache::remember("fuel-{$this->month}",36000,function (){
            return DB::table('FUEL_BY_UNIT_VIEW')
                ->selectRaw('unit as name,fuel_type, SUM(total) as total')
                ->where('month',$this->month)
                ->groupBy(['unit','fuel_type'])
                ->orderBy('total', 'DESC')
                ->take(10)
                ->get()
                ->groupBy('name');
        });

        $categories = $data->keys()->toArray();

        $data = $data->map(function ($app,$key){
            return (double)$app->sum('total');
        })->values()->toArray();
        return view('livewire.reports.fuel.cost-by-unit-chart',compact('data','categories'));
    }
}

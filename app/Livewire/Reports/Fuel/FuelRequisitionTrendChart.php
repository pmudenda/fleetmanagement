<?php

namespace App\Livewire\Reports\Fuel;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FuelRequisitionTrendChart extends Component
{
    public $month = '2023';
    public function render()
    {
        $data = Cache::remember("fuel-year-trend-{$this->month}",36000,function (){
            return DB::table('FUEL_BY_UNIT_VIEW')
                ->selectRaw('month,fuel_type, SUM(qty) as total')
                ->where('month','LIKE',"%{$this->month}%")
                ->groupBy(['month','fuel_type'])
                ->orderBy('month', 'ASC')
                ->get();
        });
        $categories = $data->keys()->toArray();

        $months = [];

        for ($i = 1;$i <= 12; $i++){
            $months[] = sprintf("%s%02d",$this->month, $i);
        }

        $months = collect($months);

        $data = $data->groupBy('fuel_type')->map(function ($app,$key) use ($months) {
            $ds = [];
            foreach ($months as $m){
                $ds[] = $app->contains('month', $m) ? (double)$app->where('month', $m)->first()->total : 0;
            }
            return [
                'name' => $key,
                'data' => $ds
            ];
        })->values()->toArray();
        return view('livewire.reports.fuel.fuel-requisition-trend-chart',compact('data','categories'));
    }
}

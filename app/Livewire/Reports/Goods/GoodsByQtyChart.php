<?php

namespace App\Livewire\Reports\Goods;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class GoodsByQtyChart extends Component {

    public function render() {
        $data = collect(DB::select("SELECT COUNT(DISTINCT H.REQ_NO) as reqs, sum(d.QUANTITY) as qty,sum(d.amount) as amt, s.name FROM 
fleetmaster.gen_material_headers h
inner join  fleetmaster.gen_material_details d on h.req_no = d.req_no
INNER JOIN fleetmaster.config_statuses s on s.code = H.STATUS and s.module ='MAT' 
where  h.is_fuel = 'Y'
GROUP BY s.name
ORDER BY sum(d.QUANTITY) DESC"))->groupBy('name');

        $categories = $data->keys()->toArray();

        $data = $data->map(function ($app) {
            return (double)$app->sum('qty');
        })->values()->toArray();
        return view('livewire.reports.goods.goods-by-qty-chart', compact('data', 'categories'));
    }
}

<?php

namespace App\Livewire\Reports\Fuel;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class FuelPeriodReport extends Component
{
    use WithPagination;
    public $from, $to, $fuel_type, $reg_no;

    protected $rules = [
        'from' => 'required|date|before:to',
        'to' => 'required|date|after:from',
        'fuel_type' => 'nullable',
        'reg_no' => 'nullable|string'
    ];

    public function mount(){
        $this->from = now()->clone()->firstOfMonth()->toDateString();
        $this->to = now()->clone()->toDateString();
    }

    public function render()
    {
        $spares = DB::table('SPARES_REPORT_VIEW')
            ->selectRaw('REG_NO,FUEL_TYPE, FUEL_REQ_UNIT, SUM(QTY) as QTY, SUM(TTL) AS TTL')
            ->where('month','>=',Carbon::createFromFormat('Y-m-d', $this->from)->format('Ym') )
            ->where('month','<=',  Carbon::createFromFormat('Y-m-d',$this->to)->format('Ym'))
            ->when($this->fuel_type, function (Builder $query) {
                $query->where('fuel_type', $this->fuel_type);
            })
            ->groupBy(['REG_NO','FUEL_TYPE','FUEL_REQ_UNIT'])
            ->orderByRaw('TTL DESC')
            ->paginate(20);

        return view('livewire.reports.fuel.fuel-period-report',compact('spares'));
    }

    public function search(){
        $this->validate();
    }
}

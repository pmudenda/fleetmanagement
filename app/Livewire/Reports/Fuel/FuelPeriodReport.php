<?php

namespace App\Livewire\Reports\Fuel;

use App\Exports\ConsolidateSparesReportExport;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\WithPagination;

class FuelPeriodReport extends Component {
    use WithPagination;

    public $from, $to, $fuel_type, $reg_no;

    protected $rules = [
        'from' => 'required|date|before:to',
        'to' => 'required|date|after:from',
        'fuel_type' => 'nullable',
        'reg_no' => 'nullable|string'
    ];

    public function mount() {
        $this->from = now()->clone()->firstOfMonth()->toDateString();
        $this->to = now()->clone()->toDateString();
    }

    public function render() {
        $spares = DB::table('ZFMS_FUEL_COST')
            ->selectRaw('REG_NO,TYPE_BRAND,FUEL_TYPE, FUEL_REQ_UNIT, SUM(QTY) as QTY, SUM(TTL) AS TTL')
            ->where('month', '>=', Carbon::createFromFormat('Y-m-d', $this->from)->format('Ym'))
            ->where('month', '<=', Carbon::createFromFormat('Y-m-d', $this->to)->format('Ym'))
            ->when($this->fuel_type, function (Builder $query) {
                $query->where('fuel_type', $this->fuel_type);
            });

        $total_quantity = $spares->sum('ttl');
        $total_amount = $spares->sum('qty');
        $spares = $spares->groupBy(['REG_NO', 'TYPE_BRAND','FUEL_TYPE', 'FUEL_REQ_UNIT'])
            ->orderByRaw('TTL DESC')->paginate(10);
        return view('livewire.reports.fuel.fuel-period-report', compact('spares', 'total_amount', 'total_quantity'));
    }

    public function search() {
        $this->validate();
    }

    public function download() {
        $this->validate();
        $columns = ['Registration Number','Fuel Type','Requesting Unit','Quantity Issued','Total Amount'];
        array_unshift($columns, '#');
        $rows = DB::table('ZFMS_FUEL_COST')
            ->selectRaw('REG_NO,FUEL_TYPE, FUEL_REQ_UNIT, SUM(QTY) as QTY, SUM(TTL) AS TTL')
            ->where('month', '>=', Carbon::createFromFormat('Y-m-d', $this->from)->format('Ym'))
            ->where('month', '<=', Carbon::createFromFormat('Y-m-d', $this->to)->format('Ym'))
            ->when($this->fuel_type, function (Builder $query) {
                $query->where('fuel_type', $this->fuel_type);
            })->groupBy(['REG_NO', 'FUEL_TYPE', 'FUEL_REQ_UNIT'])
            ->orderByRaw('TTL DESC');

        return (new ConsolidateSparesReportExport($rows, $columns))->download('ConsolidatedFuelReport.xlsx');

    }
}

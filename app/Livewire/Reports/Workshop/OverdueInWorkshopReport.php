<?php

namespace App\Livewire\Reports\Workshop;

use App\Exports\ConsolidateSparesReportExport;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\WithPagination;

class OverdueInWorkshopReport extends Component {
    use WithPagination;

    public $from, $to, $fuel_type, $reg_no;

    protected $rules = [
        'from' => 'required|date|before:to',
        'to' => 'required|date|after:from',
        'reg_no' => 'nullable|string'
    ];

    public function mount() {
        $this->from = now()->clone()->firstOfMonth()->toDateString();
        $this->to = now()->clone()->toDateString();
    }

    public function render() {
       $vehicles =  DB::table("VEHICLE_IN_WORKSHOP_OVER_90_DAYS")->get()->map(function ($vehicle) {
           $vehicle->date_in = Carbon::createFromFormat('Y-m-d H:m:s', $vehicle->date_in)->toFormattedDateString();
           $vehicle->expected_date_out = Carbon::createFromFormat('Y-m-d H:m:s', $vehicle->expected_date_out)->toFormattedDateString();
           return $vehicle;
       });
        return view('livewire.reports.workshop.overdue-in-workshop-report', compact('vehicles'));
    }

    public function search() {
        $this->validate();
        $this->resetPage();
    }

    public function download() {
        $this->validate();
        $columns = ['Reg No','Brand','Workshop Act Code','Workshop','Date In','Expected Date Out','Driver Man no','Driver Name','Days Overdue'];
        array_unshift($columns, '#');

        $rows = DB::table("VEHICLE_IN_WORKSHOP_OVER_90_DAYS")->select([
            'reg_no','brand_name','wshp_act_code','workshop_name','date_in','expected_date_out','driver_in','driver_name','days_overdue'
        ])->orderBy('days_overdue','asc');
        return (new ConsolidateSparesReportExport($rows, $columns))->download('Vehicle_Overdue_in_workshop.xlsx');

    }
}

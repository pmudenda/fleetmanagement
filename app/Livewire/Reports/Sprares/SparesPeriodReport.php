<?php

namespace App\Livewire\Reports\Sprares;

use App\Exports\ConsolidateSparesReportExport;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\WithPagination;

class SparesPeriodReport extends Component
{
    use WithPagination;
    public $from, $to, $fuel_type;

    protected $rules = [
        'from' => 'required|date',
        'to' => 'required|date',
    ];

    public function mount(){
        $this->from = now()->clone()->firstOfMonth()->toDateString();
        $this->to = now()->clone()->toDateString();
    }

    public function render()
    {
        $columns = Schema::getColumnListing('MERGED_SPARES_REPORT_VIEW');
        array_unshift($columns,'#');

        $rows = DB::table('MERGED_SPARES_REPORT_VIEW')
            ->where('UPDATED_AT','>=',$this->from )
            ->where('UPDATED_AT','<=',  $this->to);

        $total_amount = $rows->sum('VALUE_AMOUNT');
        $rows = $rows->paginate(25);

        return view('livewire.reports.sprares.spares-period-report',compact('rows','columns','total_amount'));
    }

    public function search(){
        $this->resetPage();
        $this->validate();
    }

    public function download(){
        $this->validate();
        $columns = Schema::getColumnListing('MERGED_SPARES_REPORT_VIEW');
        array_unshift($columns,'#');
        $rows = DB::table('MERGED_SPARES_REPORT_VIEW')
            ->where('UPDATED_AT','>=',$this->from )
            ->where('UPDATED_AT','<=',  $this->to)
            ->orderBy('UPDATED_AT');

        ini_set('memory_limit','-1');
        return (new ConsolidateSparesReportExport($rows,$columns))->download('ConsolidatedSparesReport.xlsx');

    }
}

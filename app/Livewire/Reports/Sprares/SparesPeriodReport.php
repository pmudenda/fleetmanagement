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
            ->where('DOCUMENT_DATE','>=',$this->from )
            ->where('DOCUMENT_DATE','<=',  $this->to);

        $total_amount = $rows->sum('VALUE_AMOUNT');
        $rows = $rows->paginate(10);

        return view('livewire.reports.sprares.spares-period-report',compact('rows','columns','total_amount'));
    }

    public function search(){
        $this->validate();
    }

    public function download(){
        $this->validate();
        $columns = Schema::getColumnListing('MERGED_SPARES_REPORT_VIEW');
        array_unshift($columns,'#');
        $rows = DB::table('MERGED_SPARES_REPORT_VIEW')
            ->where('month','>=',Carbon::createFromFormat('Y-m-d', $this->from)->format('Ym') )
            ->where('month','<=',  Carbon::createFromFormat('Y-m-d',$this->to)->format('Ym'))
            ->orderBy('month');

        ini_set('memory_limit','-1');
        return (new ConsolidateSparesReportExport($rows,$columns))->download('ConsolidatedSparesReport.xlsx');

    }
}

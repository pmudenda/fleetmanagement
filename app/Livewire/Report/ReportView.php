<?php

namespace App\Livewire\Report;

use App\Exports\GenericReportExport;
use Illuminate\Support\Facades\DB;
use Jimmyjs\ReportGenerator\ReportMedia\PdfReport;
use Livewire\Component;

class ReportView extends Component {
    public $report;
    public $columns;

    public function mount($report) {
        $this->report = (object)config('report')[$report];
    }

    public function render() {

        $results = DB::table(DB::raw("({$this->report->query})"))->paginate(10);

        // Get $this->columns
        if ($results->count() > 0) {
            $this->columns = array_keys((array)$results->first());
        } else {
            $emptyResult = DB::selectOne("SELECT * FROM ({$this->report->query}) WHERE ROWNUM = 0");
            $this->columns = array_keys((array)$emptyResult);
        }

        // Convert to lowercase

        $this->columns = array_map('strtolower', $this->columns);

        return view('livewire.report.report-view', compact('results'));
    }

    public function export() {
        $results = collect(DB::select($this->report->query));
        return (new GenericReportExport($this->columns, $results))->download("{$this->report->title}.xlsx");

    }
}

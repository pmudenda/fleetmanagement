<?php

namespace App\Livewire\Report;

use App\Models\Reports\SystemReport;
use Livewire\Component;

class ReportIndex extends Component
{
    public function render()
    {
        $reports = SystemReport::all();
        return view('livewire.report.report-index',compact('reports'));
    }
}

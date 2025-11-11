<?php

namespace App\Livewire\Report;

use Livewire\Component;

class ReportIndex extends Component
{
    public function render()
    {
        $reports = config('report');
        return view('livewire.report.report-index',compact('reports'));
    }
}

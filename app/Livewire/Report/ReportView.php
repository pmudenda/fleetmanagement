<?php

namespace App\Livewire\Report;

use App\Exports\GenericReportExport;
use App\Models\Reports\SystemReport;
use Illuminate\Support\Facades\DB;
use Jimmyjs\ReportGenerator\ReportMedia\PdfReport;
use Livewire\Component;
use Livewire\WithPagination;

class ReportView extends Component {
    use WithPagination;

    public SystemReport $report;
    public $columns;
    public $filters = [];
    public $filterOptions = [];
    public $errors = [];

    protected $rules = [
        'filters.*' => 'nullable'
    ];

    public function mount(SystemReport $report) {
        $this->report = $report;

        // Initialize filter values from the report's filters configuration
        if ($this->report->filters) {
            foreach ($this->report->filters as $filter) {
                $field = $filter['field'];
                if ($filter['type'] === 'date_range') {
                    $this->filters[$field . '_start'] = '';
                    $this->filters[$field . '_end'] = '';
                } else if ($filter['type'] === 'number_range') {
                    $this->filters[$field . '_min'] = '';
                    $this->filters[$field . '_max'] = '';
                } else {
                    $this->filters[$field] = '';
                }
            }
        }

        $this->loadFilterOptions();
    }

    public function loadFilterOptions() {
        if (!$this->report->filters) return;

        foreach ($this->report->filters as $filter) {
            // Only load options for select/multi_select filters
            if (in_array($filter['type'], ['select', 'multi_select'])) {
                try {
                    $distinctValues = DB::table(DB::raw("({$this->report->query})"))
                        ->select($filter['field'])
                        ->distinct()
                        ->whereNotNull($filter['field'])
                        ->orderBy($filter['field'])
                        ->pluck($filter['field'])
                        ->toArray();

                    $this->filterOptions[$filter['field']] = $distinctValues;
                } catch (\Exception $e) {
                    $this->filterOptions[$filter['field']] = [];
                }
            }
        }
    }

    public function applyFilters() {
        $this->errors = [];

        // Validate ranges
        if ($this->report->filters) {
            foreach ($this->report->filters as $filter) {
                $field = $filter['field'];

                if ($filter['type'] === 'date_range') {
                    $start = $this->filters[$field . '_start'];
                    $end = $this->filters[$field . '_end'];

                    if (!empty($start) && !empty($end) && $start > $end) {
                        $this->errors[$field] = "Start date cannot be greater than end date";
                        return;
                    }
                }

                if ($filter['type'] === 'number_range') {
                    $min = $this->filters[$field . '_min'];
                    $max = $this->filters[$field . '_max'];

                    if (!empty($min) && !empty($max) && $min > $max) {
                        $this->errors[$field] = "Minimum value cannot be greater than maximum value";
                        return;
                    }
                }
            }
        }

        $this->resetPage();
    }

    public function resetFilters() {
        foreach ($this->filters as $key => $value) {
            $this->filters[$key] = '';
        }
        $this->errors = [];
        $this->resetPage();
    }

    public function updatedFilters($value, $key) {
        // Real-time validation when range inputs change
        $this->errors = [];

        if ($this->report->filters) {
            foreach ($this->report->filters as $filter) {
                $field = $filter['field'];

                if ($filter['type'] === 'date_range' && (str_contains($key, $field . '_start') || str_contains($key, $field . '_end'))) {
                    $start = $this->filters[$field . '_start'];
                    $end = $this->filters[$field . '_end'];

                    if (!empty($start) && !empty($end) && $start > $end) {
                        $this->errors[$field] = "Start date cannot be greater than end date";
                    }
                }

                if ($filter['type'] === 'number_range' && (str_contains($key, $field . '_min') || str_contains($key, $field . '_max'))) {
                    $min = $this->filters[$field . '_min'];
                    $max = $this->filters[$field . '_max'];

                    if (!empty($min) && !empty($max) && $min > $max) {
                        $this->errors[$field] = "Minimum value cannot be greater than maximum value";
                    }
                }
            }
        }
    }

    public function render() {
        $query = DB::table(DB::raw("({$this->report->query})"));

        // Apply filters only if no validation errors
        if ($this->report->filters && empty($this->errors)) {
            foreach ($this->report->filters as $filter) {
                $this->applyFilterToQuery($query, $filter);
            }
        }

        $results = $query->paginate(10);

        if ($results->count() > 0) {
            $this->columns = array_keys((array)$results->first());
        } else {
            $emptyResult = DB::selectOne("SELECT * FROM ({$this->report->query}) WHERE ROWNUM = 0");
            $this->columns = array_keys((array)$emptyResult);
        }

        $this->columns = array_map('strtolower', $this->columns);

        return view('livewire.report.report-view', compact('results'));
    }

    private function applyFilterToQuery($query, $filter) {
        $field = $filter['field'];

        switch ($filter['type']) {
            case 'date_range':
                if (!empty($this->filters[$field . '_start'])) {
                    $query->where($field, '>=', $this->filters[$field . '_start']);
                }
                if (!empty($this->filters[$field . '_end'])) {
                    $query->where($field, '<=', $this->filters[$field . '_end']);
                }
                break;

            case 'text_search':
                if (!empty($this->filters[$field])) {
                    $query->where($field, 'LIKE', '%' . $this->filters[$field] . '%');
                }
                break;

            case 'multi_select':
                if (!empty($this->filters[$field])) {
                    $query->whereIn($field, $this->filters[$field]);
                }
                break;

            case 'number_range':
                if (!empty($this->filters[$field . '_min'])) {
                    $query->where($field, '>=', $this->filters[$field . '_min']);
                }
                if (!empty($this->filters[$field . '_max'])) {
                    $query->where($field, '<=', $this->filters[$field . '_max']);
                }
                break;

            case 'select':
                if (!empty($this->filters[$field])) {
                    $query->where($field, $this->filters[$field]);
                }
                break;
        }
    }

    public function export() {
        // Validate before export
        $this->applyFilters();
        if (!empty($this->errors)) {
            return;
        }

        $query = DB::table(DB::raw("({$this->report->query})"));

        // Apply filters for export too
        if ($this->report->filters) {
            foreach ($this->report->filters as $filter) {
                $this->applyFilterToQuery($query, $filter);
            }
        }

        $results = $query->get();
        return (new GenericReportExport($this->columns, $results))->download("{$this->report->name}.xlsx");
    }
}
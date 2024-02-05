<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Laravel\Scout\Builder as ScoutBuilder;
use Maatwebsite\Excel\Concerns\Exporcolumns;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ConsolidateSparesReportExport implements FromQuery, WithHeadings {
    private $query, $columns;
    use Exportable;

    /**
     * @param $query
     * @param $columns
     */
    public function __construct($query, $columns) {
        $this->query = $query;
        $this->columns = $columns;
    }


    public function query() {
        return $this->query;
    }

    public function headings(): array {
        return $this->columns;
    }
}

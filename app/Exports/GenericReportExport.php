<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GenericReportExport implements FromCollection, WithHeadings
{
    use Exportable;

    private $columns;
    private $query;

    function __construct($columns, $query) {
        $this->columns = $columns;
        $this->query = $query;
    }

    public function query() {
       return $this->query;
    }

    public function headings(): array {
        unset($this->columns[0]);
       return $this->columns;
    }

    public function collection() {
       return $this->query;
    }
}

<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NonCompliantVehicleExport implements FromCollection, WithHeadings
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }


    public function collection()
    {
        return $this->data;
    }

    public function headings(): array {
        return [
            'Registration No',
            'Licence No',
            'Valid From',
            'Valid To',
            'Cost',
            'Payment Date',
            'Order No',
            'Created By',
            'Modified By',
            'Deleted At',
            'Created At',
            'Updated At',
            'Status',
            'Fitness Expiry',
            'Is Compliant'
        ];
    }
}

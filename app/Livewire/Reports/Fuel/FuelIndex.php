<?php

namespace App\Livewire\Reports\Fuel;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

class FuelIndex extends Component
{
    #[Url]
    public $year;

    #[Url]
    public $month;

    public function mount(){
        $now = now();
        $this->year = $this->year ?? $now->year;
        $this->month = $this->month ?? sprintf("%02d",$now->month);
    }

    public function search(){
        $this->redirect(route('reports.fuel.requisitions',['month' => $this->month,'year' => $this->year]));
    }

    #[Computed]
    public function months()
    {
        $months = [];

        for ($i = 1;$i <= now()->clone()->month; $i++){
            $months[] = [
                'name' => now()->month($i)->monthName,
                'id' => sprintf("%02d", $i)
            ];
        }

        return $months;
    }
}

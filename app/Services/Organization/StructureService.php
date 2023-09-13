<?php

namespace App\Services\Organization;

use App\Models\Common\BusinessUnit;
use App\Models\Common\CostCenter;
use Illuminate\Support\Collection;

class StructureService
{
    public function getBusinessUnits(): Collection
    {
        return BusinessUnit::where('status', '=', '01')->get();
    }

    public function getCostCenters()
    {
        return CostCenter::where('status', '=', '01')->get();
    }
}

<?php

namespace App\Services\WorkShopManagement;

use App\Models\configurations\GeneralTableConfigurations;
use App\Models\WorkShopManagement\JobCardHeader;
use Illuminate\Http\Request;

class WorkshopService
{

    public function createJobCard(Request $request)
    {
        $user = auth()->user();
        $data = [
            'veh_reg' => $request->get('vehicle_registration'),
            'date_in' => $request->get('date_of_req'),
            'workshop_code' => $request->get('workshop'),
            'time_in' => $request->get('timeIn'),
            'repair_type' => $request->get('repairType'),
            'received_by' => $user->staff_no,
            'receiving_section' => GeneralTableConfigurations::where('name', '=', 'RECEPTION')->where('type', 'WORK_SHOP_SEC')->first()->code,
            'accident_ref' => $request->get('accident_number'),
            'millage_in' => $request->get('current_odometer'),
            'fuel_level_in' => $request->get('fuel_level'),
            'driver_in' => $request->get('driver_staff_number')
        ];

        JobCardHeader::create($data);
    }
}

<?php

namespace Database\Seeders;

use App\Enums\VehicleStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleModelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*DB::table('CONFIG_VEHICLE_MODELS')->insert([
            'brand_name'=>'TOYOTA',
            'status'=>VehicleStatusEnum::active,
            'date_created' => Carbon::now(),
            'brand_guid',
            '',
            'model_guid',
            'model_name',
            'model_code',
            'code',
            'date_created',
            'created_by'
        ]);*/
    }
}

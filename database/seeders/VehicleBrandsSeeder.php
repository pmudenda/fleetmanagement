<?php

namespace Database\Seeders;

use App\Enums\VehicleStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VehicleBrandsSeeder extends Seeder
{

    public function run(): void{
        DB::table('CONFIG_VEHICLE_BRANDS')->insert([
            'name'=>'TOYOTA',
            'status'=>VehicleStatusEnum::active,
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([
            'name'=>'ISUZU',
            'status'=>VehicleStatusEnum::active,
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([
            'name'=>'MAZDA',
            'status'=>VehicleStatusEnum::active,
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([

            'name'=>'HINO',
            'status'=>VehicleStatusEnum::active,
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([
            'name'=>'FORD',
            'status'=>VehicleStatusEnum::active,
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([

            'name'=>'VW',
            'status'=>VehicleStatusEnum::active,
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([
            'name'=>'AUDI',
            'status'=>VehicleStatusEnum::active,
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([
            'name'=>'BMW',
            'status'=>VehicleStatusEnum::active,
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([
            'name'=>'HAVAL',
            'status'=>VehicleStatusEnum::active,
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([
            'name'=>'HYUNDAI',
            'status'=>VehicleStatusEnum::active,
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([
            'name'=>'LAND ROVER',
            'status'=>VehicleStatusEnum::active,
            'date_created' => Carbon::now()
        ]);
    }

}

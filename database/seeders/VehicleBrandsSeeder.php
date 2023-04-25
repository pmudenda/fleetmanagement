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
            'guid'=> Str::uuid(),
            'name'=>'TOYOTA',
            'status'=>VehicleStatusEnum::active,
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([
            'guid'=> Str::uuid(),
            'name'=>'ISUZU',
            'status'=>VehicleStatusEnum::active,
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([
            'guid'=> Str::uuid(),
            'name'=>'MAZDA',
            'status'=>VehicleStatusEnum::active,
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([
            'guid'=> Str::uuid(),
            'name'=>'HINO',
            'status'=>VehicleStatusEnum::active,
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([
            'guid'=> Str::uuid(),
            'name'=>'FORD',
            'status'=>VehicleStatusEnum::active,
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([
            'guid'=> Str::uuid(),
            'name'=>'VW',
            'status'=>VehicleStatusEnum::active,
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([
            'guid'=> Str::uuid(),
            'name'=>'AUDI',
            'status'=>VehicleStatusEnum::active,
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([
            'guid'=> Str::uuid(),
            'name'=>'BMW',
            'status'=>VehicleStatusEnum::active,
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([
            'guid'=> Str::uuid(),
            'name'=>'LAND ROVER',
            'status'=>VehicleStatusEnum::active,
            'date_created' => Carbon::now()
        ]);
    }

}

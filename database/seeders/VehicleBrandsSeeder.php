<?php

namespace Database\Seeders;

use App\Helpers\StatusHelper;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleBrandsSeeder extends Seeder
{

    public function run(): void{
        DB::table('CONFIG_VEHICLE_BRANDS')->insert([
            'name'=>'TOYOTA',
            'status'=>StatusHelper::active(),
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([
            'name'=>'ISUZU',
            'status'=>StatusHelper::active(),
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([
            'name'=>'MAZDA',
            'status'=>StatusHelper::active(),
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([

            'name'=>'HINO',
            'status'=>StatusHelper::active(),
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([
            'name'=>'FORD',
            'status'=>StatusHelper::active(),
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([

            'name'=>'VW',
            'status'=>StatusHelper::active(),
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([
            'name'=>'AUDI',
            'status'=>StatusHelper::active(),
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([
            'name'=>'BMW',
            'status'=>StatusHelper::active(),
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([
            'name'=>'HAVAL',
            'status'=>StatusHelper::active(),
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([
            'name'=>'HYUNDAI',
            'status'=>StatusHelper::active(),
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BRANDS')->insert([
            'name'=>'LAND ROVER',
            'status'=>StatusHelper::active(),
            'date_created' => Carbon::now()
        ]);
    }

}

<?php

namespace Database\Seeders;

use App\Enums\VehicleStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VehicleBodyTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('CONFIG_VEHICLE_BODY_TYPES')->insert([
            'name'=>'VAN',
            'status'=>VehicleStatusEnum::active,
            'body_type_name' =>'VAN',
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BODY_TYPES')->insert([
            'name'=>'SEDAN',
            'status'=>VehicleStatusEnum::active,
            'body_type_name' =>'SEDAN',
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BODY_TYPES')->insert([
            'name'=>'TRUCK',
            'status'=>VehicleStatusEnum::active,
            'body_type_name' =>'BUS',
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BODY_TYPES')->insert([
            'name'=>'BUS',
            'status'=>VehicleStatusEnum::active,
            'body_type_name' =>'BUS',
            'date_created' => Carbon::now()
        ]);
        DB::table('CONFIG_VEHICLE_BODY_TYPES')->insert([
            'name'=>'SUV',
            'status'=>VehicleStatusEnum::active,
            'body_type_name' =>'SUV',
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BODY_TYPES')->insert([
            'name'=>'PICK UP',
            'status'=>VehicleStatusEnum::active,
            'body_type_name' =>'PICK UP',
            'date_created' => Carbon::now()
        ]);


        DB::table('CONFIG_VEHICLE_BODY_TYPES')->insert([

            'name'=>'COUPE',
            'status'=>VehicleStatusEnum::active,
            'body_type_name' =>'PICK UP',
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BODY_TYPES')->insert([
            'name'=>'STATION WAGON',
            'status'=>VehicleStatusEnum::active,
            'body_type_name' =>'STATION WAGON',
            'date_created' => Carbon::now()
        ]);
    }
}

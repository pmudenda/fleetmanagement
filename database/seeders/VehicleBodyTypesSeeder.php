<?php

namespace Database\Seeders;

use App\Helpers\StatusHelper;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleBodyTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('CONFIG_VEHICLE_BODY_TYPES')->insert([
            'name'=>'VAN',
            'status'=>StatusHelper::active(),
            'body_type_name' =>'VAN',
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BODY_TYPES')->insert([
            'name'=>'SEDAN',
            'status'=>StatusHelper::active(),
            'body_type_name' =>'SEDAN',
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BODY_TYPES')->insert([
            'name'=>'TRUCK',
            'status'=>StatusHelper::active(),
            'body_type_name' =>'TRUCK',
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BODY_TYPES')->insert([
            'name'=>'BUS',
            'status'=>StatusHelper::active(),
            'body_type_name' =>'BUS',
            'date_created' => Carbon::now()
        ]);
        DB::table('CONFIG_VEHICLE_BODY_TYPES')->insert([
            'name'=>'SUV',
            'status'=>StatusHelper::active(),
            'body_type_name' =>'SUV',
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BODY_TYPES')->insert([
            'name'=>'PICK UP',
            'status'=>StatusHelper::active(),
            'body_type_name' =>'PICK UP',
            'date_created' => Carbon::now()
        ]);


        DB::table('CONFIG_VEHICLE_BODY_TYPES')->insert([

            'name'=>'COUPE',
            'status'=>StatusHelper::active(),
            'body_type_name' =>'COUPE',
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BODY_TYPES')->insert([
            'name'=>'STATION WAGON',
            'status'=>StatusHelper::active(),
            'body_type_name' =>'STATION WAGON',
            'date_created' => Carbon::now()
        ]);
    }
}

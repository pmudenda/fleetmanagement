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
        DB::table('CONFIG_VEHICLE_BODY_TYPES')
            ->insert(DB::raw("insert into CONFIG_VEHICLE_BODY_TYPES('guid','name','status', 'body_type_name',  'date_created' ) values (sys_guid(),'VAN', '01','VAN', current_timestamp()) "));

            /*=>,
            =>,
            =>VehicleStatusEnum::active,
             =>,
            => Carbon::now()
        ]));*/

       DB::table('CONFIG_VEHICLE_BODY_TYPES')->insert([
            'guid'=> 'sys_guid',
            'name'=>'SEDAN',
            'status'=>VehicleStatusEnum::active,
            'body_type_name' =>'SEDAN',
            'date_created' => Carbon::now()
        ]);

        /* DB::table('CONFIG_VEHICLE_BODY_TYPES')->insert([
            'guid'=> sys_guid(),
            'name'=>'TRUCK',
            'status'=>VehicleStatusEnum::active,
            'body_type_name' =>'BUS',
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BODY_TYPES')->insert([
            'guid'=> sys_guid(),
            'name'=>'BUS',
            'status'=>VehicleStatusEnum::active,
            'body_type_name' =>'BUS',
            'date_created' => Carbon::now()
        ]);
        DB::table('CONFIG_VEHICLE_BODY_TYPES')->insert([
            'guid'=> sys_guid(),
            'name'=>'SUV',
            'status'=>VehicleStatusEnum::active,
            'body_type_name' =>'SUV',
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BODY_TYPES')->insert([
            'guid'=> sys_guid(),
            'name'=>'PICK UP',
            'status'=>VehicleStatusEnum::active,
            'body_type_name' =>'PICK UP',
            'date_created' => Carbon::now()
        ]);


        DB::table('CONFIG_VEHICLE_BODY_TYPES')->insert([
            'guid'=> sys_guid(),
            'name'=>'COUPE',
            'status'=>VehicleStatusEnum::active,
            'body_type_name' =>'PICK UP',
            'date_created' => Carbon::now()
        ]);

        DB::table('CONFIG_VEHICLE_BODY_TYPES')->insert([
            'guid'=> sys_guid(),
            'name'=>'STATION WAGON',
            'status'=>VehicleStatusEnum::active,
            'body_type_name' =>'STATION WAGON',
            'date_created' => Carbon::now()
        ]);*/
    }
}

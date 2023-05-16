<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleAccessoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('VM_VEHICLE_ACCESSORIES')->insert([
            'name' => 'AERIAL VHF',
            'status' => '01',
            'code' => '01'
        ]);
        DB::table('VM_VEHICLE_ACCESSORIES')->insert([
            'name' => 'CASSETTE',
            'status' => '02',
            'code' => '02'
        ]);
        DB::table('VM_VEHICLE_ACCESSORIES')->insert([
            'name' => 'CD PLAYER',
            'status' => '01',
            'code' => '03'
        ]);
        DB::table('VM_VEHICLE_ACCESSORIES')->insert([
            'name' => 'FIRE EXTINGUISHER',
            'status' => '01',
            'code' => '04'
        ]);
        DB::table('VM_VEHICLE_ACCESSORIES')->insert([
            'name' => 'FIRST AID BOX',
            'status' => '01',
            'code' => '05'
        ]);
        DB::table('VM_VEHICLE_ACCESSORIES')->insert([
            'name' => 'GEAR LOCK',
            'status' => '01',
            'code' => '06'
        ]);
        DB::table('VM_VEHICLE_ACCESSORIES')->insert([
            'name' => 'HUB CAPS',
            'status' => '01',
            'code' => '07'
        ]);
        DB::table('VM_VEHICLE_ACCESSORIES')->insert([
            'name' => 'JACK',
            'status' => '01',
            'code' => '08'
        ]);
        DB::table('VM_VEHICLE_ACCESSORIES')->insert([
            'name' => 'JACK HANDLE(S)',
            'status' => '01',
            'code' => '09'
        ]);
        DB::table('VM_VEHICLE_ACCESSORIES')->insert([
            'name' => 'OWNER BOOK MANUAL',
            'status' => '01',
            'code' => '10'
        ]);
        DB::table('VM_VEHICLE_ACCESSORIES')->insert([
            'name' => 'RADIO,',
            'status' => '01',
            'code' => '11'
        ]);
        DB::table('VM_VEHICLE_ACCESSORIES')->insert([
            'name' => 'RUBBER MATS',
            'status' => '01',
            'code' => '12'
        ]);
        DB::table('VM_VEHICLE_ACCESSORIES')->insert([
            'name' => 'SEAT BELTS',
            'status' => '01',
            'code' => '13'
        ]);
        DB::table('VM_VEHICLE_ACCESSORIES')->insert([
            'name' => 'SEAT COVERS',
            'status' => '01',
            'code' => '14'
        ]);
        DB::table('VM_VEHICLE_ACCESSORIES')->insert([
            'name' => 'SPARE WHEEL',
            'status' => '01',
            'code' => '15'
        ]);
        DB::table('VM_VEHICLE_ACCESSORIES')->insert([
            'name' => 'TOOL KIT',
            'status' => '01',
            'code' => '16'
        ]);
        DB::table('VM_VEHICLE_ACCESSORIES')->insert([
            'name' => 'TRIANGLES',
            'status' => '01',
            'code' => '17'
        ]);
        DB::table('VM_VEHICLE_ACCESSORIES')->insert([
            'name' => 'UHF AERIAL',
            'status' => '01',
            'code' => '18'
        ]);

        DB::table('VM_VEHICLE_ACCESSORIES')->insert([
            'name' => 'WHEEL SPANNER',
            'status' => '01',
            'code' => '19'
        ]);

    }
}

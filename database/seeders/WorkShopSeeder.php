<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '18',
            'workshop_name' => 'CHINGOLA MECHANICAL WORKSHOP',
            'area_code' => 'CR',
            'status' => '01',
            'user_unit' => 'DA220',
            'store_code' => '6800'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '32',
            'workshop_name' => 'CHINSALI MECHANICAL WORKSHOP',
            'area_code' => 'NR',
            'status' => '01',
            'user_unit' => 'D5201',
            'store_code' => '8900'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '16',
            'workshop_name' => 'CHIPATA  MECHANICAL WORKSHOP',
            'area_code' => 'LR',
            'status' => '01',
            'user_unit' => 'D7222',
            'store_code' => '4900'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '31',
            'workshop_name' => 'CHISHIMBA FALLS MECHANICAL WORKSHOP',
            'area_code' => 'GR',
            'user_unit' => 'G2505',
            'status' => '01',
            'store_code' => '7900'
            //'user_unit' => '',
            //'business_unit' => '',
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '15',
            'workshop_name' => 'CHOMA MECHANICAL WORKSHOP',
            'area_code' => 'LR',
            'status' => '01',
            'user_unit' => 'D5921',
            'store_code' => '4400'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '16',
            'workshop_name' => 'LUSIWASI MECHANICAL WORKSHOP',
            'area_code' => '10',
            'status' => '01',
            'user_unit' => 'G2323',
            'store_code' => '7800'
        ]);


        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '19',
            'workshop_name' => 'MUFULIRA MECHANICAL WORKSHOP',
            'area_code' => 'CR',
            'status' => '01',
            'user_unit' => 'D9822',
            'store_code' => '6700'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '26',
            'workshop_name' => 'ITEZHI TEZHI MECHANICAL WORKSHOP',
            'area_code' => 'GR',
            'status' => '01',
            'user_unit' => 'G2016',
            'store_code' => '7400'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '25',
            'workshop_name' => 'MONZE MECHANICAL WORKSHOP',
            'area_code' => 'LR',
            'status' => '01',
            'user_unit' => 'D6121',
            'store_code' => '4500'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '27',
            'workshop_name' => 'MUSONDA FALLS',
            'area_code' => 'GR',
            'status' => '01',
            'user_unit' => 'G2416',
            'store_code' => '7700'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '35',
            'workshop_name' => 'KAFUE GORGE LOWER MECHANICAL & TRANSPORT',
            'area_code' => 'GR',
            'status' => '01',
            'user_unit' => 'G1800',
            'store_code' => '7200'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '03',
            'workshop_name' => 'LIVINGSTONE MECHANICAL',
            'area_code' => 'LR',
            'status' => '01',
            'user_unit' => 'D5822',
            'store_code' => '4300'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '14',
            'workshop_name' => 'MAZABUKA MECHANICAL WORKSHOP',
            'area_code' => 'SR',
            'status' => '01',
            'user_unit' => 'D6021',
            'store_code' => '4600'
        ]);


        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '02',
            'workshop_name' => 'NDOLA MECHANICAL WORKSHOP',
            'area_code' => 'NR',
            'status' => '01',
            'user_unit' => 'D5622',
            'store_code' => '2100'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '07',
            'workshop_name' => 'MANSA MECHANICAL WORKSHOP',
            'area_code' => 'NR',
            'status' => '01',
            'user_unit' => 'D3822',
            'store_code' => '3700'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '24',
            'workshop_name' => 'MPIKA WORKSHOP',
            'area_code' => 'NR',
            'status' => '01',
            'user_unit' => 'D5421',
            'store_code' => '3000'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '23',
            'workshop_name' => 'KITWE G&T MECHANICAL WORKSHOP',
            'area_code' => 'CR',
            'status' => '01',
            'user_unit' => 'D9920',
            'store_code' => '6600'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '21',
            'workshop_name' => 'MKUSHI MECHANICAL WORKSHOP',
            'area_code' => 'SR',
            'status' => '01',
            'user_unit' => 'D8221',
            'store_code' => '6600'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '08',
            'workshop_name' => 'KASAMA MECHANICAL WORKSHOP',
            'area_code' => 'NR',
            'status' => '01',
            'user_unit' => 'D4722',
            'store_code' => '2900'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '01',
            'workshop_name' => 'LUSAKA MECHANICAL WORKSHOP',
            'area_code' => 'LR',
            'status' => '01',
            'user_unit' => 'C1923',
            'store_code' => '1100'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '05',
            'workshop_name' => 'KAFUE GORGE MECHANICAL WORKSHOP',
            'area_code' => 'GR',
            'status' => '01',
            'user_unit' => 'G1916',
            'store_code' => '7200'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '06',
            'workshop_name' => 'KARIBA NORTH BANK MECHANICAL WORKSHOP',
            'area_code' => 'GR',
            'status' => '01',
            'user_unit' => 'G2600',
            'store_code' => '8200'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '04',
            'workshop_name' => 'KITWE MECHANICAL WORKSHOP',
            'area_code' => 'CR',
            'status' => '01',
            'user_unit' => 'D9920',
            'store_code' => '6600'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '12',
            'workshop_name' => 'KABWE  MECHANICAL WORKSHOP',
            'area_code' => 'LR',
            'status' => '01',
            'user_unit' => 'D8122',
            'store_code' => '5600'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '33',
            'workshop_name' => 'TRANSMISSION SOUTH  SOUTHERN REGION WORKSOP',
            'area_code' => 'LR',
            'status' => '01',
            'user_unit' => 'T3207',
            'store_code' => '7300'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '17',
            'workshop_name' => 'VICTORIA FALLS MECHANICAL WORKSHOP',
            'area_code' => 'GR',
            'status' => '01',
            'user_unit' => 'G2116',
            'store_code' => '7300'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '13',
            'workshop_name' => 'MONGU MECHANICAL WORKSHOP',
            'area_code' => 'LR',
            'status' => '01',
            'user_unit' => 'D6722',
            'store_code' => '6000'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '09',
            'workshop_name' => 'SOLWEZI  MECHANICAL WORKSHOP',
            'area_code' => 'CR',
            'status' => '01',
            'user_unit' => 'D3444',
            'store_code' => '2300'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '20',
            'workshop_name' => 'LUANSHYA MECHANICAL WORKSHOP',
            'area_code' => 'NR',
            'status' => '01',
            'user_unit' => 'DA821',
            'store_code' => '6900'
        ]);

    }
}

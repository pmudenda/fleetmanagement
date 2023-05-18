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
            'workshop_code' => '31',
            'workshop_name' => 'CHISHIMBA FALLS MECHANICAL WORKSHOP',
            'area_code' => 'GR',
            'cost_center' => 'G2505',
            'status' => '01',
            //'user_unit' => '',
            //'business_unit' => '',
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '32',
            'workshop_name' => 'CHINSALI MECHANICAL WORKSHOP',
            'area_code' => 'NR',
            'status' => '01',
            'cost_center' => 'D5201',
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '16',
            'workshop_name' => 'LUSIWASI MECHANICAL WORKSHOP',
            'area_code' => '10',
            'status' => '01',
            'cost_center' => 'G2323',
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '16',
            'workshop_name' => 'CHIPATA  MECHANICAL WORKSHOP',
            'area_code' => 'LR',
            'status' => '01',
            'cost_center' => 'D7222',
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '19',
            'workshop_name' => 'MUFULIRA MECHANICAL WORKSHOP',
            'area_code' => 'CR',
            'status' => '01',
            'cost_center' => 'D9822',
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '26',
            'workshop_name' => 'ITEZHI TEZHI MECHANICAL WORKSHOP',
            'area_code' => 'GR',
            'status' => '01',
            'cost_center' => 'G2016',
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '25',
            'workshop_name' => 'MONZE MECHANICAL WORKSHOP',
            'area_code' => 'LR',
            'status' => '01',
            'cost_center' => 'D6121',
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '27',
            'workshop_name' => 'MUSONDA FALLS',
            'area_code' => 'GR',
            'status' => '01',
            'cost_center' => 'G2416',
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '35',
            'workshop_name' => 'KAFUE GORGE LOWER MECHANICAL & TRANSPORT',
            'area_code' => 'GR',
            'status' => '01',
            'cost_center' => 'G1800'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '03',
            'workshop_name' => 'LIVINGSTONE MECHANICAL',
            'area_code' => 'LR',
            'status' => '01',
            'cost_center' => 'D5822'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '14',
            'workshop_name' => 'MAZABUKA MECHANICAL WORKSHOP',
            'area_code' =>'SR',
            'status' => '01',
            'cost_center' => 'D6021'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '15',
            'workshop_name' => 'CHOMA MECHANICAL WORKSHOP',
            'area_code' => 'LR',
            'status' => '01',
            'cost_center' => 'D5921'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '02',
            'workshop_name' => 'NDOLA MECHANICAL WORKSHOP',
            'area_code' => 'NR',
            'status' => '01',
            'cost_center' => 'D5622'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '07',
            'workshop_name' => 'MANSA MECHANICAL WORKSHOP',
            'area_code' =>'NR',
            'status' => '01',
            'cost_center' => 'D3822'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '24',
            'workshop_name' => 'MPIKA WORKSHOP',
            'area_code' => 'NR',
            'status' => '01',
            'cost_center' => 'D5421'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '23',
            'workshop_name' => 'KITWE G&T MECHANICAL WORKSHOP',
            'area_code' =>'CR',
            'status' => '01',
            'cost_center' => 'D9920',
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '21',
            'workshop_name' => 'MKUSHI MECHANICAL WORKSHOP',
            'area_code' => 'SR',
            'status' => '01',
            'cost_center' => 'D8221',
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '08',
            'workshop_name' => 'KASAMA MECHANICAL WORKSHOP',
            'area_code' =>'NR',
            'status' => '01',
            'cost_center' => 'D4722',
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '01',
            'workshop_name' => 'LUSAKA MECHANICAL WORKSHOP',
            'area_code' => 'LR',
            'status' => '01',
            'cost_center' => 'C1923',
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '05',
            'workshop_name' => 'KAFUE GORGE MECHANICAL WORKSHOP',
            'area_code' =>'GR',
            'status' => '01',
            'cost_center' => 'G1916',
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '06',
            'workshop_name' => 'KARIBA NORTH BANK MECHANICAL WORKSHOP',
            'area_code' => 'GR',
            'status' => '01',
            'cost_center' => 'G2600',
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '04',
            'workshop_name' => 'KITWE MECHANICAL WORKSHOP',
            'area_code' =>'CR',
            'status' => '01',
            'cost_center' => 'D9920',
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '12',
            'workshop_name' => 'KABWE  MECHANICAL WORKSHOP',
            'area_code' => 'LR',
            'status' => '01',
            'cost_center' => 'D8122',
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '33',
            'workshop_name' => 'TRANSMISSION SOUTH  SOUTHERN REGION WORKSOP',
            'area_code' => 'LR',
            'status' => '01',
            'cost_center' => 'T3207'
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '17',
            'workshop_name' => 'VICTORIA FALLS MECHANICAL WORKSHOP',
            'area_code' => 'GR',
            'status' => '01',
            'cost_center' => 'G2116',
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '13',
            'workshop_name' => 'MONGU MECHANICAL WORKSHOP',
            'area_code' => 'LR',
            'status' => '01',
            'cost_center' => 'D6722',
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '18',
            'workshop_name' => 'CHINGOLA MECHANICAL WORKSHOP',
            'area_code' => 'CR',
            'status' => '01',
            'cost_center' => 'DA220',
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '09',
            'workshop_name' => 'SOLWEZI  MECHANICAL WORKSHOP',
            'area_code' => 'CR',
            'status' => '01',
            'cost_center' => 'D3444',
        ]);

        DB::table('CONFIG_WORKSHOP')->insert([
            'workshop_code' => '20',
            'workshop_name' => 'LUANSHYA MECHANICAL WORKSHOP',
            'area_code' => 'NR',
            'status' => '01',
            'cost_center' => 'DA821',
        ]);

    }
}

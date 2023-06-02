<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'ACTIVE',
            'code' => '01',
            'active'=>1,
            'module' => 'ALL',
        ]);

        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'INACTIVE',
            'code' => '01',
            'active'=>1,
            'module' => 'OGS',
        ]);

        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'ACTIVE',
            'code' => '00',
            'active'=>1,
            'module' => 'OGS',
        ]);

        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'INACTIVE',
            'code' => '02',
            'active'=>1,
            'module' => 'ALL',

        ]);

        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'HANDED OVER',
            'code' => '03',
            'active'=>1,
            'module' => 'ALL',

        ]);

        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'GROUNDED',
            'code' => '004',
            'active'=>1,
            'module' => 'ALL',

        ]);


        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'VEHICLE IN WORKSHOP',
            'code' => '005',
            'active'=>1,
            'module' => 'ALL',

        ]);

        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'SCRAP',
            'code' => '006',
            'active'=>1,
            'module' => 'ALL',

        ]);

        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'STOLEN',
            'code'=> '007',
            'active'=>1,
            'module' => 'ALL',

        ]);

        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'DISPOSED/SOLD',
            'code'=> '008',
            'active'=>1,
            'module' => 'ALL',

        ]);

        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'PENDING DISPOSAL-HAS REACHED LEAST VALUE',
            'active'=>1,
            'code'=> '009',
            'module' => 'ALL',

        ]);

        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'SALVAGE',
            'code'=> '010',
            'active'=>1,
            'module' => 'ALL',
        ]);

        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'RE REGISTERED',
            'code'=> '011',
            'active'=>1,
            'module' => 'ALL',
        ]);


        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'NEW',
            'code' => '01',
            'active'=>1,
            'module' => 'MAT',
        ]);

        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'AUTHORISED',
            'code' => '02',
            'active'=>1,
            'module' => 'MAT',
        ]);

        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'REJECTED',
            'code' => '03',
            'active'=>1,
            'module' => 'MAT',
        ]);

        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'ISSUED',
            'code' => '04',
            'active'=>1,
            'module' => 'MAT',
        ]);  DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'VERIFIED',
            'code' => '05',
            'active'=>1,
            'module' => 'MAT',
        ]);

        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'PARTIALLY AUTHORIZED',
            'code' => '21',
            'active'=>1,
            'module' => 'MAT',
        ]);

        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'PARTIALLY RELEASED',
            'code' => '26',
            'active'=>1,
            'module' => 'MAT',
        ]);

        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'RELEASED',
            'code' => '025',
            'active'=>1,
            'module' => 'MAT',
        ]);

        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'EXPIRED',
            'code' => '026',
            'active'=>1,
            'module' => 'MAT',

        ]);

        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'PART RELEASE EXPIRED',
            'code' => '027',
            'active'=>1,
            'module' => 'MAT',
        ]);

        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'CANCELLED',
            'code' => '027',
            'active'=>1,
            'module' => 'MAT',
        ]);

        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'AUTHORIZED',
            'code' => '02',
            'active'=>1,
            'module' => 'MAT',
        ]);

        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'PARTIALLY RELEASED CANCELLED',
            'code' => '027',
            'active'=>1,
            'module' => 'MAT',
        ]);

        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'COMPLETED',
            'code' => '030',
            'active'=>1,
            'module' => 'OB',

        ]);

        DB::table('CONFIG_STATUSES')->insert([
            'description'=> '',
            'name'=>'PENDING',
            'code' => '031',
            'active'=>1,
            'module' => 'OB',

        ]);
    }
}

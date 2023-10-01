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

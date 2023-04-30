<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitOfMeasureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('CONFIG_UNIT_OF_MEASURES')->insert([
            'name' => 'EACH',
            'short_name' => 'Ea',
            'code' => '01',
            'status' => '01'
        ]);
        DB::table('CONFIG_UNIT_OF_MEASURES')->insert([
            'name' => 'LITRE',
            'short_name' => 'Ltr',
            'code' => '02',
            'status' => '01'
        ]);

        DB::table('CONFIG_UNIT_OF_MEASURES')->insert([
            'name' => 'BOX',
            'short_name' => 'Bx',
            'code' => '03',
            'status' => '01'
        ]);
    }
}

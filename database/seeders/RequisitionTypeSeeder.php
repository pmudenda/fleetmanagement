<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RequisitionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('CONFIG_REQUISITION_TYPES')->insert([
            'name' => 'Normal',
            'code' => '010',
            'status' => '01',
            'module' => 'FR'
        ]);

        DB::table('CONFIG_REQUISITION_TYPES')->insert([
            'name' => 'Out 0f Tow',
            'code' => '011',
            'status' => '01',
            'module' => 'FR',
        ]);
        DB::table('CONFIG_REQUISITION_TYPES')->insert([
            'name' => 'Override',
            'code' => '012',
            'status' => '01',
            'module' => 'FR',
        ]);
    }
}

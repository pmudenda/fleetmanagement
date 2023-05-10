<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DirectoratesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('REF_DIRECTORATES')->insert([
            'name' => 'POWER GENERATION',
            'code' => 'GE',
            'active' => '00',
        ]);

        DB::table('REF_DIRECTORATES')->insert([
            'name' => 'TRANSMISSION OPERATIONS & TRADE',
            'code' => 'TR',
            'active' => '00',
        ]);

        DB::table('REF_DIRECTORATES')->insert([
            'name' => 'HUMAN CAPITAL & DEVELOPMENT',
            'code' => 'HC',
            'active' => '00',
        ]);

        DB::table('REF_DIRECTORATES')->insert([
            'name' => 'INVESTMENTS & FINANCE',
            'code' => 'FN',
            'active' => '00',
        ]);

        DB::table('REF_DIRECTORATES')->insert([
            'name' => 'LEGAL SERVICES & COMPANY SECRETARY',
            'code' => 'LG',
            'active' => '00',
        ]);

        DB::table('REF_DIRECTORATES')->insert([
            'name' => 'CORPORATE SUPPORT SERVICES',
            'code' => 'CS',
            'active' => '00',
        ]);

        DB::table('REF_DIRECTORATES')->insert([
            'name' => 'PLANNING & PROJECTS',
            'code' => 'PP',
            'active' => '00',
        ]);

        DB::table('REF_DIRECTORATES')->insert([
            'name' => 'DISTRIBUTION AND CUSTOMER SERVICE',
            'code' => 'DS',
            'active' => '00',
        ]);
        DB::table('REF_DIRECTORATES')->insert([
            'name' => 'MANAGING DIRECTOR',
            'code' => 'MD',
            'active' => '00',
        ]);

        DB::table('REF_DIRECTORATES')->insert([
            'name' => 'CORPORATE WIDE',
            'code' => 'CO',
            'active' => '01',
        ]);
    }
}

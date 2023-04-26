<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Predis\Command\Traits\DB;

class DirectoratesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::table('REF_DIRECTORATES')->insert([
            'name' => 'POWER GENERATION',
            'active' => 1,
        ]);

        \Illuminate\Support\Facades\DB::table('REF_DIRECTORATES')->insert([
            'name' => 'TRANSMISSION OPERATIONS & TRADE',
            'active' => 1,
        ]);

        \Illuminate\Support\Facades\DB::table('REF_DIRECTORATES')->insert([
            'name' => 'HUMAN CAPITAL & DEVELOPMENT',
            'active' => 1,
        ]);

        \Illuminate\Support\Facades\DB::table('REF_DIRECTORATES')->insert([
            'name' => 'INVESTMENTS & FINANCE',
            'active' => 1,
        ]);

        \Illuminate\Support\Facades\DB::table('REF_DIRECTORATES')->insert([
            'name' => 'LEGAL SERVICES & COMPANY SECRETARY',
            'active' => 1,
        ]);

        \Illuminate\Support\Facades\DB::table('REF_DIRECTORATES')->insert([
            'name' => 'CORPORATE SUPPORT SERVICES',
            'active' => 1,
        ]);

        \Illuminate\Support\Facades\DB::table('REF_DIRECTORATES')->insert([
            'name' => 'PLANNING & PROJECTS',
            'active' => 1,
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganogramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = 'database/db_scripts/BUSINESS_UNITS.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Business Units table seeded!');

        $path = 'database/db_scripts/COST_CENTERS.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Cost Centers table seeded!');

        $path = 'database/db_scripts/organizational_units.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Organizational Units table seeded!');
    }
}

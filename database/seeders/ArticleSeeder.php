<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('GEN_ARTICLES')->insert([
            'name' => 'Diesel',
            'description' => 'Fuel',
            'code' => '01',
            'status' => '01',
            'price' => '28.65',
            'unit_of_measure_code' => '02',
            'group_code' => '01'
        ]);

        DB::table('GEN_ARTICLES')->insert([
            'name' => 'Petrol',
            'description' => 'Fuel',
            'code' => '02',
            'status' => '01',
            'price' => '27.65',
            'unit_of_measure_code' => '02',
            'group_code' => '01'
        ]);
    }
}

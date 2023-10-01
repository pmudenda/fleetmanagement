<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('SEC_USERS')->insert([
            'name' => 'Lovemore Daka',
            'email' => 'admin@zfm.zesco.co.zm',
            'password' => Hash::make('welcome123'),
            'staff_no' => 'X5999',
            'username' => 'X5999',
        ]);

    }
}

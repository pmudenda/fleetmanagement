<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ArticleSeeder::class,
            DefaultUserSeeder::class,
            DirectoratesSeeder::class,
            RequisitionTypeSeeder::class,
            RolesAndPermissionsSeeder::class,
            StatusSeeder::class,
            UnitOfMeasureSeeder::class,
            VehicleBodyTypesSeeder::class,
            VehicleBrandsSeeder::class,
            //OrganogramSeeder::class
            WorkflowSeeder::class,
        ]);
    }
}

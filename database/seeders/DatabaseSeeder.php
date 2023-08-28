<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            WorkShopSeeder::class,
            DefaultUserSeeder::class,
            DirectoratesSeeder::class,
            RequisitionTypeSeeder::class,
            RolesSeeder::class,
            StatusSeeder::class,
            VehicleBodyTypesSeeder::class,
            VehicleBrandsSeeder::class,
            WorkflowSeeder::class,
            WorkShopSeeder::class,
            VehicleAccessoriesSeeder::class,

            AssignPermissionsToRoleSeeder::class
        ]);
    }
}

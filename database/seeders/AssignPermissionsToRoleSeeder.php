<?php

namespace Database\Seeders;

use App\Models\Security\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AssignPermissionsToRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $adminRole = Role::where('name', '=', 'super_user')->first();
        $adminRole->syncPermissions(Permission::all()->pluck('name'));
    }
}

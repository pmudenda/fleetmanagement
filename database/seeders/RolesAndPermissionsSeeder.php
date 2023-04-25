<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Permission::create(['description' => 'Add User', 'name' => 'can-add-user', 'slug' => 'add_user']);
        Permission::create(['description' => 'View User Detail', 'name' => 'view-user-detail', 'slug' => 'view_user_detail']);
        Permission::create(['description' => 'View Users', 'name' => 'view-user', 'slug' => 'view_user']);
        Permission::create(['description' => 'Allow User to On-Board Vehicle', 'name' => 'on-board-vehicle', 'slug' => 'on_board_vehicle']);


        Role::create(
            [
                'description' => 'Default',
                'name' => 'default',
                'slug' => 'default'
            ]
        );

        Role::create(
            [
                'description' => 'Super User',
                'name' => 'super_user',
                'slug' => 'super_user'
            ]
        );

        Role::create(
            [
                'description' => 'Fuel Requistioner',
                'name' => 'fuel-requistioner',
                'slug' => 'fuel_requistioner'
            ]
        );


        Role::create(
            [
                'description' => 'Auditor',
                'name' => 'auditor',
                'slug' => 'auditor'
            ]
        );

        Role::create(
            [
                'description' => 'System Administrator',
                'name' => 'system-administrator',
                'slug' => 'system_administrator',
            ]
        );

        Role::create(
            [
                'description' => 'Transport Manager',
                'name' => 'transport-manager',
                'slug' => 'transport_manager'
            ]
        );

        Role::create(
            [
                'description' => 'Transport Officer',
                'name' => 'transport-officer',
                'slug'=> 'transport_officer'
            ]
        );

        Role::create(
            [
                'description' => 'Data Entry Clerk',
                'name' => 'data-entry-clerk',
                'slug' => 'data_entry_clerk'
            ]
        );

        Role::create(
            [
                'description' => 'Mechanic',
                'name' => 'mechanic',
                'slug' => 'mechanic'
            ]
        );


    }
}

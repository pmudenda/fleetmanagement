<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
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
        Permission::create(['description' => 'Allow User to View Vehicle Details', 'name' => 'view-vehicle-details', 'slug' => 'view_vehicle_details']);


        Role::create(
            [
                'description' => 'Default',
                'name' => 'default',
                'slug' => 'default'
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
                'description' => 'Transport Controller',
                'name' => 'transport-controller',
                'slug' => 'transport_controller',
            ]
        );

        Role::create(
            [
                'description' => 'Driver',
                'name' => 'driver',
                'slug' => 'driver',
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
                'description' => 'Managers',
                'name' => 'managers',
                'slug' => 'managers'
            ]
        );

        Role::create(
            [
                'description' => 'Fuel Requisitioning',
                'name' => 'fuel-requisitioning',
                'slug' => 'fuel_requisitioning'
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
                'description' => 'Insurance Manager',
                'name' => 'insurance-manager',
                'slug' => 'insurance_manager'
            ]
        );

        Role::create(
            [
                'description' => 'Insurance Officer',
                'name' => 'insurance-officer',
                'slug' => 'insurance_officer'
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
                'slug' => 'transport_officer'
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

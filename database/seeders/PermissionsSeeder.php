<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Cache::flush('spatie.permission.cache');
        Cache::flush('spatie.role.cache');

        // Vehicle Management
        Permission::firstOrCreate(['description' => 'Allow User to On-Board Vehicle', 'name' => 'on_board_vehicle', 'slug' => 'on_board_vehicle']);
        Permission::firstOrCreate(['description' => 'Allow User to View Vehicle Details', 'name' => 'view_vehicle_details', 'slug' => 'view_vehicle_details']);
        Permission::firstOrCreate(['description' => 'Allow User to View Vehicle Documents', 'name' => 'view_vehicle_docs', 'slug' => 'view_vehicle_docs']);
        Permission::firstOrCreate(['description' => 'Allow User to View Vehicle Details', 'name' => 'edit_vehicle_details', 'slug' => 'edit_vehicle_details']);
        Permission::firstOrCreate(['description' => 'Add Vehicle Accessories', 'name' => 'create_veh_accessories', 'slug' => 'create_veh_accessories']);


        Permission::firstOrCreate(['description' => 'Add Vehicle Brands', 'name' => 'add_vehicle_brand', 'slug' => 'add_vehicle_brand']);
        Permission::firstOrCreate(['description' => 'Add Vehicle Body Types', 'name' => 'add_vehicle_type', 'slug' => 'add_vehicle_type']);
        Permission::firstOrCreate(['description' => 'Add Vehicle Body Types', 'name' => 'add_vehicle_model', 'slug' => 'add_vehicle_model']);
        Permission::firstOrCreate(['description' => 'Set Fuel Allocation', 'name' => 'set_vehicle_fuel_allocation', 'slug' => 'set_vehicle_fuel_allocation']);
        Permission::firstOrCreate(['description' => 'Set Vehicle Charge-Out Rate Allocation', 'name' => 'create_veh_charge_out_rate', 'slug' => 'create_veh_charge_out_rate']);

        // User Management
        Permission::firstOrCreate(['description' => 'User Access', 'name' => 'user_access', 'slug' => 'user_access']);
        Permission::firstOrCreate(['description' => 'Onboard User', 'name' => 'user_create', 'slug' => 'user_create']);
        Permission::firstOrCreate(['description' => 'Allows User to update another users details', 'name' => 'user_update', 'slug' => 'user_update']);
        Permission::firstOrCreate(['description' => 'User Destroy', 'name' => 'user_destroy', 'slug' => 'user_destroy']);
        Permission::firstOrCreate(['description' => 'Can View User', 'name' => 'user_show', 'slug' => 'user_show']);

        Permission::firstOrCreate(['description' => 'Add User', 'name' => 'can_add_user', 'slug' => 'add_user']);
        Permission::firstOrCreate(['description' => 'View User Detail', 'name' => 'view_user_detail', 'slug' => 'view_user_detail']);
        Permission::firstOrCreate(['description' => 'View Users', 'name' => 'view_user', 'slug' => 'view_user']);
        Permission::firstOrCreate(['description' => 'user_attach', 'name' => 'user_attach', 'slug' => 'user_attach']);

        // Fuel Requisitions
        Permission::firstOrCreate(['description' => 'Allows User to requisition for fuel', 'name' => 'requisition_fuel', 'slug' => 'requisition_fuel']);
        Permission::firstOrCreate(['description' => 'Allows user to requisition for jobCard spares', 'name' => 'requisition_spares', 'slug' => 'requisition_spares']);
        Permission::firstOrCreate(['description' => 'Allows User to approve fuel requisition', 'name' => 'approve_fuel_requisition', 'slug' => 'approve_fuel_requisition']);


        // Workshop
        Permission::firstOrCreate(['description' => 'Create Workshop Section', 'name' => 'add_workshop_section', 'slug' => 'create_work_section']);
        Permission::firstOrCreate(['description' => 'Edit Workshop Section', 'name' => 'edit_workshop_section', 'slug' => 'edit_work_section']);
        Permission::firstOrCreate(['description' => 'View Workshop Section', 'name' => 'view_workshop_section', 'slug' => 'view_work_section']);


        // Permissions -Security
        Permission::firstOrCreate(['description' => 'Permission Access', 'name' => 'permission_access', 'slug' => 'permission_access']);
        Permission::firstOrCreate(['description' => 'Permission Show', 'name' => 'permission_show', 'slug' => 'permission_show']);
        Permission::firstOrCreate(['description' => 'Can Edit Permission', 'name' => 'permission_edit', 'slug' => 'permission_edit']);
        Permission::firstOrCreate(['description' => 'Permission Destroy', 'name' => 'permission_destroy', 'slug' => 'permission_destroy']);
        Permission::firstOrCreate(['description' => 'Create Permission', 'name' => 'permission_create', 'slug' => 'permission_create']);
        Permission::firstOrCreate(['description' => 'Assign Permission', 'name' => 'permission_attach', 'slug' => 'permission_attach']);
        Permission::firstOrCreate(['description' => 'Permission Detach', 'name' => 'permission_revoke', 'slug' => 'permission_revoke']);


        Permission::firstOrCreate(['description' => 'Add General Tables Data', 'name' => 'add_general_table_data', 'slug' => 'add_general_table_data']);
        Permission::firstOrCreate(['description' => 'Access Reports', 'name' => 'access_reports', 'slug' => 'access_reports']);

        // Roles | Profile -Security
        Permission::firstOrCreate(['description' => 'Can Detach Role To User ', 'name' => 'user_detach', 'slug' => 'user_detach']);
        Permission::firstOrCreate(['description' => 'Create Role', 'name' => 'role_create', 'slug' => 'role_create']);
        Permission::firstOrCreate(['description' => 'View System Role Access', 'name' => 'role_access', 'slug' => 'has_system_role_access']);
        Permission::firstOrCreate(['description' => 'Role Show', 'name' => 'role_show', 'slug' => 'can_role_show']);
        Permission::firstOrCreate(['description' => 'Edit User Role', 'name' => 'role_edit', 'slug' => 'role_edit']);
        Permission::firstOrCreate(['description' => 'Destroy User Role', 'name' => 'role_destroy', 'slug' => 'role_destroy']);
        Permission::firstOrCreate(['description' => 'Assign Role Attach', 'name' => 'role_attach', 'slug' => 'role_attach']);
        Permission::firstOrCreate(['description' => 'Remove User Role Detach', 'name' => 'role_detach', 'slug' => 'role_detach']);
    }
}

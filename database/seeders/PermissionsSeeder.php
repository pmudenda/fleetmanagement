<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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

        // Vehicle Management
        Permission::create(['description' => 'Allow User to On-Board Vehicle', 'name' => 'on_board_vehicle', 'slug' => 'on_board_vehicle']);
        Permission::create(['description' => 'Allow User to View Vehicle Details', 'name' => 'view_vehicle_details', 'slug' => 'view_vehicle_details']);
        Permission::create(['description' => 'Allow User to View Vehicle Documents', 'name' => 'view_vehicle_docs', 'slug' => 'view_vehicle_docs']);
        Permission::create(['description' => 'Allow User to View Vehicle Details', 'name' => 'view_vehicle_details', 'slug' => 'view_vehicle_details']);
        Permission::create(['description' => 'Add Vehicle Accessories', 'name' => 'create_veh_accessories', 'slug' => 'create_veh_accessories']);


        Permission::create(['description' => 'Add Vehicle Brands', 'name' => 'add_vehicle_brand', 'slug' => 'add_vehicle_brand']);
        Permission::create(['description' => 'Add Vehicle Body Types', 'name' => 'add_vehicle_type', 'slug' => 'add_vehicle_type']);
        Permission::create(['description' => 'Add Vehicle Body Types', 'name' => 'add_vehicle_model', 'slug' => 'add_vehicle_model']);
        Permission::create(['description' => 'Set Fuel Allocation', 'name' => 'set_vehicle_fuel_allocation', 'slug' => 'set_vehicle_fuel_allocation']);
        Permission::create(['description' => 'Set Vehicle Charge-Out Rate Allocation', 'name' => 'create_veh_charge_out_rate', 'slug' => 'create_veh_charge_out_rate']);

        // User Management
        Permission::create(['description' => 'User Access', 'name' => 'user_access', 'slug' => 'user_access']);
        Permission::create(['description' => 'User Create', 'name' => 'user_create', 'slug' => 'user_create']);
        Permission::create(['description' => 'Allows User to update another users details', 'name' => 'user_update', 'slug' => 'user_update']);
        Permission::create(['description' => 'User Destroy', 'name' => 'user_destroy', 'slug' => 'user_destroy']);
        Permission::create(['description' => 'Can View User', 'name' => 'user_show', 'slug' => 'user_show']);

        Permission::create(['description' => 'Add User', 'name' => 'can_add_user', 'slug' => 'add_user']);
        Permission::create(['description' => 'View User Detail', 'name' => 'view_user_detail', 'slug' => 'view_user_detail']);
        Permission::create(['description' => 'View Users', 'name' => 'view_user', 'slug' => 'view_user']);
        Permission::create(['description' => 'user_attach', 'name' => 'user_attach', 'slug' => 'user_attach']);

        // Fuel Requisitions
        Permission::create(['description' => 'Allows User to requisition for fuel', 'name' => 'requisition_fuel', 'slug' => 'requisition_fuel']);
        Permission::create(['description' => 'Allows user to requisition for maintenance spares', 'name' => 'requisition_spares', 'slug' => 'requisition_spares']);
        Permission::create(['description' => 'Allows User to approve fuel requisition', 'name' => 'approve_fuel_requisition', 'slug' => 'approve_fuel_requisition']);


        // Workshop
        Permission::create(['description' => 'Create Workshop Section', 'name' => 'add_workshop_section', 'slug' => 'create_work_section']);
        Permission::create(['description' => 'Edit Workshop Section', 'name' => 'edit_workshop_section', 'slug' => 'edit_work_section']);
        Permission::create(['description' => 'View Workshop Section', 'name' => 'view_workshop_section', 'slug' => 'view_work_section']);


        // Permissions -Security
        Permission::create(['description' => 'Permission Access', 'name' => 'permission_access', 'slug' => 'permission_access']);
        Permission::create(['description' => 'Permission Show', 'name' => 'permission_show', 'slug' => 'permission_show']);
        Permission::create(['description' => 'Can Edit Permission', 'name' => 'permission_edit', 'slug' => 'permission_edit']);
        Permission::create(['description' => 'Permission Destroy', 'name' => 'permission_destroy', 'slug' => 'permission_destroy']);
        Permission::create(['description' => 'Can Permission Create', 'name' => 'permission_create', 'slug' => 'permission_create']);
        Permission::create(['description' => 'Assign Permission', 'name' => 'permission_attach', 'slug' => 'permission_attach']);
        Permission::create(['description' => 'Permission Detach', 'name' => 'permission_revoke', 'slug' => 'permission_revoke']);


        Permission::create(['description' => 'Add General Tables Data', 'name' => 'add_general_table_data', 'slug' => 'add_general_table_data']);
        Permission::create(['description' => 'Access Reports', 'name' => 'access_reports', 'slug' => 'access_reports']);

        // Roles | Profile -Security
        Permission::create(['description' => 'Can Detach Role To User ', 'name' => 'user_detach', 'slug' => 'user_detach']);
        Permission::create(['description' => 'Can Create Role', 'name' => 'role_create', 'slug' => 'role_create']);
        Permission::create(['description' => 'View System Role Access', 'name' => 'role_access', 'slug' => 'has_system_role_access']);
        Permission::create(['description' => 'Role Show', 'name' => 'role_show', 'slug' => 'can_role_show']);
        Permission::create(['description' => 'Edit User Role', 'name' => 'role_edit', 'slug' => 'role_edit']);
        Permission::create(['description' => 'Destroy User Role', 'name' => 'role_destroy', 'slug' => 'role_destroy']);
        Permission::create(['description' => 'Assign Role Attach', 'name' => 'role_attach', 'slug' => 'role_attach']);
        Permission::create(['description' => 'Remove User Role Detach', 'name' => 'role_detach', 'slug' => 'role_detach']);
    }
}

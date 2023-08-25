<?php

namespace App\Providers;

use App\Models\Security\Permission;
use App\Models\Security\Role;
use Illuminate\Support\ServiceProvider;

class RightsServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        //dynamic constants
        try {
            $permissions = Permission::all();
            foreach ($permissions as $permission) {
                config(['rights.' . $permission->slug => $permission->slug]);
            }

            $roles = Role::all();
            foreach ($roles as $role) {
                config(['roles.' . $role->slug => $role->slug]);
            }
        } catch (\Exception $e) {
            //ignored
        }
    }

    public function register()
    {

    }

}

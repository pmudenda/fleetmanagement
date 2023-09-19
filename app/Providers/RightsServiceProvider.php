<?php

namespace App\Providers;

use App\Models\Security\Permission;
use Illuminate\Support\ServiceProvider;

class RightsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        try {

            $permissions = Permission::all();

            foreach ($permissions as $permission) {
                config(['rights.' . trim(strtolower($permission->slug)) => trim(strtolower($permission->slug))]);
            }

            /*$roles = Role::all();
            foreach ($roles as $role) {
                config(['roles.' . $role->slug => $role->slug]);
            }*/
        } catch (\Exception $e) {
            //ignored
        }
    }
}

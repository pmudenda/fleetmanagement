<?php

namespace App\Providers;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use App\Models\Security\Permission;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;

class PermissionsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        try {
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            Cache::flush('spatie.permission.cache');
            Cache::flush('spatie.role.cache');

            Permission::get()->map(function ($permission) {
                Gate::define(trim(strtolower($permission->slug)), function ($user) use ($permission) {
                   return $user-> hasPermissionTo($permission);
                });
            });
        } catch (\Exception $e) {
            //
        }
    }

}

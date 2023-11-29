<?php

namespace App\Models\Security\Permissions;

use App\Models\Security\Permission;
use App\Models\Security\Role;
use App\Models\Security\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasPermissionsTrait
{

    public function givePermissionsTo(... $permissions): static
    {
        $permissions = $this->getAllPermissions($permissions);

        if ($permissions === null) {
            return $this;
        }
        $this->permissions()->saveMany($permissions);
        return $this;
    }

    public function withdrawPermissionsTo(... $permissions): static
    {
        $permissions = $this->getAllPermissions($permissions);
        $this->permissions()->detach($permissions);
        return $this;

    }

    public function refreshPermissions(... $permissions): User
    {
        $this->permissions()->detach();
        return $this->givePermissionsTo($permissions);
    }

    public function hasPermissionTo($permission): bool
    {
        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
    }

    public function hasPermissionThroughRole($permission): bool
    {
        foreach ($permission->roles as $role) {
            if ($this->roles->contains($role)) {
                return true;
            }
        }
        return false;
    }

    public function hasRole(... $roles): bool
    {

        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * indicates which table shows the roles a user has
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'SEC_USER_ROLES');
    }

    /**
     * indicates which table we get the permissions that a user has
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'SEC_USER_PERMISSIONS');
    }

    protected function hasPermission($permission): bool
    {
        return (bool)$this->permissions->where('slug', strtolower(trim($permission->slug)))->count();
    }

    protected function getAllPermissions(array $permissions)
    {
        return Permission::whereIn('slug', $permissions)->get();
    }

}

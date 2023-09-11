<?php

namespace App\Services\Security;

use App\Exceptions\DataNotFoundException;
use App\Helpers\StatusHelper;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\Security\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class RoleService
{
    /**
     * @param int $id
     * @param mixed $slug
     * @param mixed $roleName
     * @param mixed $roleDescription
     * @return array
     * @throws DataNotFoundException
     */
    public function updateRoles(int $id, mixed $slug, mixed $roleName, mixed $roleDescription): array
    {
        DB::executeProcedure('pkg_sec_roles.proc_update_role', [
            'p_id' => $id,
            'p_code' => 'PROFILE_0' . $id,
            'p_status' => StatusHelper::active(),
            'p_description' => $roleDescription,
            'p_slug' => $slug,
            'p_name' => $roleName
        ]);

        return FleetMasterJsonResponse::response(
            'success',
            true,
            'Role ' . $roleName . ' updated Successfully'
        );
    }

    public function deleteRole(mixed $id): void
    {
        DB::executeProcedure('pkg_sec_roles.proc_delete_role', ['p_id' => $id]);
    }

    public function createRole(mixed $slug, mixed $roleName, mixed $roleDescription): void
    {
        DB::executeProcedure('pkg_sec_roles.proc_create_role', [
            'p_code' => 'PROFILE_0',
            'p_status' => StatusHelper::active(),
            'p_description' => $roleDescription,
            'p_slug' => $slug,
            'p_guard_name' => 'web',
            'p_name' => $roleName
        ]);

        /*DB::beginTransaction();
        $slug = strtolower(str_replace(' ', '-', $request->slug));
        Role::updateOrCreate(
            [
                'slug' => $slug,
            ],
            [
                'slug' => $slug,
                'name' => $request->name,
                'description' => $request->name,
                'guard_name' => 'web'
            ]
        );
        DB::commit();*/
    }

    public function get(): Collection
    {
       return Role::all();
    }

}

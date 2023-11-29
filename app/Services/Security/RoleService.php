<?php

namespace App\Services\Security;

use App\Exceptions\DataNotFoundException;
use App\Exceptions\RecordCreationException;
use App\Helpers\StatusHelper;
use App\Http\Requests\PermissionAssignment;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\Security\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDO;

class RoleService
{
    const RESULT = ":result";

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

    /**
     * @throws RecordCreationException
     */
    public function createRole(mixed $slug, mixed $roleName, mixed $roleDescription): void
    {

        $pdo = DB::getPdo();
        $stmt = $pdo->prepare(
            "begin :result := pkg_sec_roles.fn_create_role(:p_code,:p_status,
            :p_description,:p_slug,:p_guard_name,:p_name); end;"
        );

        $profileCode = 'PROFILE_0';
        $status = StatusHelper::active();
        $guardName = 'web';
        $stmt->bindParam(self::RESULT, $results, PDO::PARAM_STR, 2000);
        $stmt->bindParam(":p_status", $status);
        $stmt->bindParam(":p_code", $profileCode);
        $stmt->bindParam(":p_description", $roleDescription);
        $stmt->bindParam(":p_slug", $slug);
        $stmt->bindParam(":p_guard_name", $guardName);
        $stmt->bindParam(":p_name", $roleName);
        $stmt->execute();

        Log::info($results);

        if (str_starts_with($results, "0")) {
            throw new RecordCreationException($results);
        }
    }

    public function get(): Collection
    {
        return Role::all();
    }

    public function assignRoles(PermissionAssignment $request): void
    {
        DB::beginTransaction();
        $role = Role::find((int)$request->get('roleId'));

        $permissionIdArray = [];
        foreach ($request->get('permissionIds') as $id) {
            $permissionIdArray[] = $id;
        }
        $role->permissions()->syncWithoutDetaching($permissionIdArray);
        DB::commit();
    }

}

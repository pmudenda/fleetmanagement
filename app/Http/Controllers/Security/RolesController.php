<?php

namespace App\Http\Controllers\Security;

use App\Constants\SystemMessages;
use App\Exceptions\RecordCreationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionAssignment;
use App\Http\Requests\RoleUpdate;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\Security\Permission;
use App\Models\Security\Role;
use App\Services\Security\RoleService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RolesController extends Controller
{
    private readonly RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(): Factory|View|Application
    {
        $roles = $this->roleService->get();
        return view('modules.security.roles.index')
            ->with(compact('roles'));
    }


    public function create(): Factory|View|Application
    {
        return view('modules.security.roles.create');
    }


    public function store(Request $request): JsonResponse
    {
        try {
            $slug = $request->name;
            $roleName = $request->name;
            $roleDescription = $request->name;
            $this->roleService->createRole($slug, $roleName, $roleDescription);
            return response()->json(
                FleetMasterJsonResponse::response(
                    '',
                    true,
                    'Profile Creation Successful..'
                ));
        } catch (RecordCreationException $e) {
            Log::error($e);
            $message = $e->getMessage();
            return response()->json(
                FleetMasterJsonResponse::response(
                    'failure',
                    false,
                    $message
                )
            );
        }
    }


    public function show(Role $role): Factory|View|Application
    {
        $permissions = Permission::all();
        return view('modules.security.roles.show')
            ->with(compact(
                'role',
                'permissions'
            ));
    }

    public function assignPermission(PermissionAssignment $request): JsonResponse
    {

        try {
            $this->roleService->assignRoles($request);
            return response()->json(
                FleetMasterJsonResponse::response(
                    'success',
                    true,
                    SystemMessages::PERMISSIONS_ATTACHED
                )
            );
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(
                FleetMasterJsonResponse::response(
                    'failure',
                    false,
                    'Permissions Could Not Be Updated'
                )
            );
        }
    }

    public function revokePermission(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        $role = Role::find($request->get('roleId'));
        $role->permissions()->detach($request->get('permission_id'));
        DB::commit();
        return redirect()
            ->back()
            ->with('message', SystemMessages::PERMISSIONS_DETACHED);
    }

    public function updateRole(RoleUpdate $request): JsonResponse
    {
        $roleName = null;
        try {

            $slug = $request->name;
            $roleName = $request->name;
            $roleDescription = $request->name;

            $response = $this->roleService->updateRoles(
                (int)$request->get('id'),
                $slug,
                $roleName,
                $roleDescription
            );

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(
                FleetMasterJsonResponse::response(
                    'failure',
                    false,
                    'Role ' . $roleName . ' Could Not Be Updated'
                )
            );
        }
    }


    public function destroy(Role $role): RedirectResponse
    {
        $this->roleService->deleteRole($role->id);
        return redirect()->back()
            ->with('message', 'Role Deleted Successfully');
    }
}

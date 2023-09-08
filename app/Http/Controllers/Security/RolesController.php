<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\Security\Permission;
use App\Models\Security\Role;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RolesController extends Controller
{

    public function index(): Factory|View|Application
    {
        $roles = Role::all();
        return view('modules.security.roles.index')
            ->with(compact('roles'));
    }


    public function create(): Factory|View|Application
    {
        return view('modules.security.roles.create');
    }


    public function store(Request $request): RedirectResponse
    {
        DB::beginTransaction();
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
        DB::commit();
        return redirect()->route('roles.index')
            ->with('message', 'Role Successfully defined..');
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

    public function assignPermission(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        $role = Role::find((int)$request->get('roleId'));
        $role->permissions()->syncWithoutDetaching($request->permission_ids);
        DB::commit();
        return redirect()
            ->back()
            ->with('message', 'Permissions Assigned Successfully..');
    }

    public function revokePermission(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        $role = Role::find($request->get('roleId'));
        $role->permissions()->detach($request->permission_id);
        DB::commit();
        return redirect()
            ->back()
            ->with('message', 'Permissions Successfully detached..');
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        Log::info("Slug $request->slug");
        Log::info("Name & Description $request->name");
        DB::beginTransaction();
        $role->slug = $request->slug;
        $role->name = $request->name;
        $role->description = $request->name;
        $role->save();
        DB::commit();

        return redirect()
            ->back()
            ->with('message', 'Role ' . $role->name . ' updated Successfully');
    }


    public function destroy(Role $role): RedirectResponse
    {
        DB::beginTransaction();
        Role::destroy($role->id);
        DB::commit();
        return redirect()->back()
            ->with('message', 'Role Deleted Successfully');
    }
}

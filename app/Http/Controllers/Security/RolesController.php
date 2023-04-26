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

class RolesController extends Controller
{

    public function index(): Factory|View|Application
    {
        $roles = Role::all();
        return view('modules.security.roles.index')->with(compact('roles'));
    }


    public function create(): Factory|View|Application
    {
        return view('modules.security.roles.create');
    }


    public function store(Request $request): RedirectResponse
    {
        Role::updateOrCreate(
            [
                'name' => $request->name,
                'slug' => $request->slug
            ],
        [
            'name' => $request->name,
            'slug' => $request->slug
        ]
        );
        return redirect()->route('roles.index')->with('message','Role Successfully defined..');
    }


    public function show(Role $role): Factory|View|Application
    {
        $permissions = Permission::all();
        return view('modules.security.roles.show')->with(compact('role','permissions'));
    }


    public function edit($id)
    {
        //
    }


    public function attach(Request $request, $id): RedirectResponse
    {
        $role = Role::find($id);
        $role->permissions()->syncWithoutDetaching($request->permission_ids);
        return redirect()->back()->with('message','Permissions Successfully attached..');
    }

    public function detach(Request $request, $id): RedirectResponse
    {
        $role = Role::find($id);
        $role->permissions()->detach($request->permission_id);
        return redirect()->back()->with('message','Permissions Successfully detached..');
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $role->name = $request->name ;
        $role->slug = $request->slug ;
        $role->save();
        return redirect()->back()->with('message', 'Role '.$role->name .' updated Successfully') ;
    }


    public function destroy(Role $role): RedirectResponse
    {
        Role::destroy($role->id);
        return redirect()->back()->with('message', 'Role Deleted Successfully') ;
    }
}

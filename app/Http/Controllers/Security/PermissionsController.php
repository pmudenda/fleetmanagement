<?php

namespace App\Http\Controllers\Security;

use App\Constants\SystemMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\SystemPermission;
use App\Models\Security\Permission;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{

    public function index()
    {
        $permissions = Permission::all();
        return view('modules.security.permissions.index')
            ->with(compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Application|Factory
     */
    public function create(): Application|Factory
    {
        return view('modules.security.permissions.create');
    }

    public function store(SystemPermission $request): RedirectResponse
    {
        $slug = str_replace(' ', '-', $request->name);
        Permission::updateOrCreate(
            [
                'name' => $request->name,
                'slug' => $slug,
                'guard_name' => 'web'
            ],
            [
                'name' => $request->name,
                'slug' => $slug,
                'guard_name' => 'web',
                'description' => $request->description
            ]
        );
        return redirect()->route('permissions.index')
            ->with('message', 'User permission Successfully defined..');
    }


    public function update(Request $request, Permission $permission): RedirectResponse
    {
        //$permission->name = $request->name ;
        //$permission->slug = $request->slug ;
        $permission->description = $request->description;
        $permission->save();
        return redirect()->back()->with('message', 'Permission ' . $permission->name . ' Updated Successfully');
    }


    public function destroy(Permission $permission): RedirectResponse
    {
        Permission::destroy($permission->id);
        return redirect()->back()->with('message', 'Deleted Successfully');
    }
}

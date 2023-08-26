<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\Security\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{

    public function index()
    {
        $permissions = Permission::all();
        return view('modules.security.permissions.index')->with(compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('modules.security.permissions.create');
    }


    public function store(Request $request)
    {
        Permission::updateOrCreate(
            [
                'name'=>$request->name,
                'slug'=>$request->slug
            ],
            [
                'name'=>$request->name,
                'slug'=>$request->slug
            ]
        );
        return redirect()->route('permissions.index')->with('message','User permission Successfully defined..');
    }


    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $permission->name = $request->name ;
        $permission->slug = $request->slug ;
        $permission->save();
        return redirect()->back()->with('message', 'Permission '.$permission->name .' updated Successfully') ;
    }


    public function destroy(Permission $permission)
    {
        Permission::destroy($permission->id);
        return redirect()->back()->with('message', 'Deleted Successfully') ;
    }
}

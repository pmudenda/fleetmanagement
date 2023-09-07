<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Http\Requests\SystemPermission;
use App\Models\Security\Permission;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PermissionsController extends Controller
{

    public function index(): View
    {
        $permissions = Permission::all();
        return view('modules.security.permissions.index')
            ->with(compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     * @return View
     */
    public function create(): View
    {
        return view('modules.security.permissions.create');
    }

    public function store(SystemPermission $request): JsonResponse
    {
        try {
            Log::info($request->des);
            $slug = strtolower(str_replace(' ', '-', $request->name));
            Permission::updateOrCreate(
                [
                    'slug' => $slug,
                    'guard_name' => 'web',
                ],
                [
                    'name' => $request->name,
                    'slug' => $slug,
                    'guard_name' => 'web',
                    'description' => $request->description
                ]
            );
            return response()->json([
                'state' => 'success',
                'message' => 'Permission Added..'
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'message' => 'Permission Added..'
            ]);
        }
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

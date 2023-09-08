<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Http\Requests\SystemPermission;
use App\Models\Security\Permission;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionsController extends Controller
{

    public function index(): View
    {
        $permissions = Permission::all();
        return view('modules.security.permissions.index')
            ->with(compact('permissions'));
    }

    public function store(SystemPermission $request): JsonResponse
    {
        try {
            Log::info("" .$request->description);
            $slug = strtolower(str_replace(' ', '-', $request->name));

            DB::beginTransaction();
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
            DB::commit();

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
        DB::beginTransaction();
        $permission->description = $request->description;
        $permission->save();
        DB::commit();
        return redirect()->back()
            ->with('message',
                'Permission ' . $permission->name . ' Updated Successfully'
            );
    }


    public function destroy(Permission $permission): RedirectResponse
    {
        DB::beginTransaction();
        Permission::destroy($permission->id);
        DB::commit();
        return redirect()->back()->with('message', 'Deleted Successfully');
    }
}

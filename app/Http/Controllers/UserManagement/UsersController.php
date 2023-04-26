<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Security\PHCMSEmployee;
use App\Models\Security\Role;
use App\Models\Security\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{

    public function index(): Factory|View|Application
    {
        $users = User::select('*')->get();
        return view('modules.security.users.index')->with(compact('users'));
    }

    public function getCurrentUserDetails(): JsonResponse
    {
        return response()->json(array(
            'state' => 'success',
            'data' => Auth::user()
        ));
    }

    public function get(Request $request): JsonResponse
    {
        $sortField = $request->input('sortField');
        $sortOrder = $request->input('sortOrder');
        $pageIndex = $request->input('pageIndex');
        $users = User::paginate($request->input('pageSize'));
        return response()->json(
            [
                'data' => $users,
                'itemsCount' => User::get()->count()
            ]
        );
    }

    public function create(): View
    {
        if (auth()->user()->hasRole(config('roles.super_user'))) {
            $roles = Role::get();
        } elseif (auth()->user()->hasRole(config('roles.system_administrator'))) {
            $roles = Role::whereNotIn('slug', [config('roles.super_user')])->get();
        } elseif (auth()->user()->hasRole(config('roles.service_desk_manager'))) {
            $roles = Role::whereNotIn('slug', [
                config('roles.super_user')
            ])->get();
        } elseif (auth()->user()->hasRole(config('roles.service_desk_supervisor'))) {
            $roles = Role::whereNotIn('slug', [
                config('roles.super_user'),
            ])->get();
        } else {
            $roles = Role::whereNotIn('slug', [
                config('roles.super_user'),
            ])->get();
        }
        return view('UserManagement.addUser')->with(compact('roles'));
    }


    public function store(Request $request): RedirectResponse
    {

        try {
            $employee_phcms = PHCMSEmployee::where('con_per_no', $request->staff_no)->first();
        } catch (\Exception $exception) {
            $employee_phcms = User::where('staff_no', $request->staff_no)->first();
        }

        if (!empty($employee_phcms)) {

            $employee = User::firstOrCreate(
                [
                    'staff_no' => $employee_phcms->con_per_no,
                    'name' => $employee_phcms->name,
                    'nrc' => $employee_phcms->nrc,
                    'sex' => $employee_phcms->sex,
                    'email' => $employee_phcms->staff_email,
                ],
                [
                    'staff_no' => $employee_phcms->con_per_no,
                    'contract_type' => $employee_phcms->contract_type,
                    'con_st_code' => $employee_phcms->con_st_code,
                    'con_wef_date' => $employee_phcms->con_wef_date,
                    'con_wet_date' => $employee_phcms->con_wet_date,
                    'name' => $employee_phcms->name,
                    'nrc' => $employee_phcms->nrc,
                    'sex' => $employee_phcms->sex,
                    'mobile_no' => $employee_phcms->mobile_no,
                    'group_type' => $employee_phcms->group_type,
                    'job_title' => $employee_phcms->job_title,
                    'grade' => $employee_phcms->grade,
                    'functional_section' => $employee_phcms->functional_section,
                    'directorate' => $employee_phcms->directorate,
                    'location' => $employee_phcms->location ?? $employee_phcms->functional_section,
                    'pay_point' => $employee_phcms->pay_point,
                    'bu_code' => $employee_phcms->bu_code,
                    'cc_code' => $employee_phcms->cc_code,
                    'email' => $employee_phcms->staff_email,
                    'job_code' => $employee_phcms->job_code ?? "--",
                    'station' => $employee_phcms->station ?? "--",
                    'affiliated_union' => $employee_phcms->affiliated_union ?? "--",
                    'password' => Hash::make($request->password),
                    'status_id' => $request->status_id,
                ]
            );
            $employee->serviceDesks()->syncWithoutDetaching($request->location_id);
            $employee->roles()->syncWithoutDetaching($request->user_role_id);
            $employee->save();

            return redirect()->back()->with('message', 'Employee has successfully been created..');
        } else {
            $message = "User Failed to be created because the Staff number (" . $request->staff_no . ") could not be verified with PHCMS.";
            return redirect()->back()->with('error', $message);
        }

    }

    public function show(User $user): Factory|View|Application
    {
        $roles = Role::all();
        return view('modules.security.users.show')->with(compact('user', 'roles'));
    }


    public function edit($id)
    {
        //
    }

    public function attach(Request $request, $id): RedirectResponse
    {

        try {
            $user = User::find($id);
            $user->roles()->syncWithoutDetaching($request->role_ids);
            return redirect()->back()->with('message', 'User Successfully Added To Selected Groups ..');
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->with('error', 'User Could Not Be Added To Groups..');
        }
    }

    public function detach(Request $request, $id): RedirectResponse
    {
        $user = User::find($id);
        $user->roles()->detach($request->role_id);
        return redirect()->back()->with('message', 'Role Successfully detached..');
    }


    public function update(Request $request, $id): void
    {
        $name = $request->input('stud_name');
        DB::update('update SEC_USERS set name = ? where id = ?', [$name, $id]);
        echo "Record updated successfully.<br/>";
        echo '<a href = "/edit-records">Click Here</a> to go back.';
    }


    public function destroy(User $user)
    {
//        $user->delete();
//
//         return redirect()->route('user.index')->with('message','User Deleted successfully');
    }


    public function search($search): JsonResponse
    {

        $dataset = PHCMSEmployee::select('*')
            ->where('con_per_no', $search)
            ->first();

        $results = [
            "user" => $dataset
        ];

        return response()->json($results);
    }


    /* Process the logout request */
    /* public function logout(Request $request): \Illuminate\Routing\Redirector|Application|RedirectResponse
     {
         $user =\auth()->user();
         $user->current_login = config('constants.current_login_false') ;
         $user->save();
         Auth::logout();
         return redirect('/login')->with(['msg_body' => 'You signed out!']);
     }*/

}

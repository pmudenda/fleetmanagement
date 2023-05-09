<?php

namespace App\Http\Controllers\UserManagement;

use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Models\general\BusinessUnits;
use App\Models\general\CostCenters;
use App\Models\reference\PHCMSEmployee;
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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UsersController extends Controller
{

    public function index(): Factory|View|Application
    {
        $users = User::select('*')->get();
        return view('UserManagement.index')->with(compact('users'));
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
        $roles = Role::get();
        $businessUnits = BusinessUnits::where('status', '=', '01')->get();
        $costCenters = CostCenters::where('status', '=', '01')->get();

        return view('UserManagement.addUser')
            ->with(compact('roles', 'businessUnits', 'costCenters'));
    }


    public function store(Request $request): JsonResponse
    {
        try {

            $employee_phcms = PHCMSEmployee::where('con_per_no', $request->staff_number)->first();

            //will be profile assigned
            DB::beginTransaction();
            $employee = User::firstOrCreate(
                [
                    'staff_no' => $request->staff_number,
                ],
                [
                    'con_st_code' => StatusHelper::active(),
                    'password' => Hash::make($request->password),
                    'email' => $request->staff_email,
                    'username' => $request->login_name,
                    'phone' => $request->mobile_no,

                    'functional_section' => $request->user_unit,
                    //'name' => $request->name,
                    //'staff_no' => $request->staff_number,
                    //'mobile_no' => $request->mobile_no,
                    //'grade' => $request->grade,
                    //'job_title' => $request->job_title,
                    'bu_code' => $request->business_unit_code,
                    'cc_code' => $request->cost_center_code,
                    'directorate' => $request->directorate,
                    'user_unit' => $request->user_unit,
                    'supervisor_code' => $request->staff_supervisorId,
                    'supervisor_name' => $request->staff_supervisor,

                    'staff_no' => $employee_phcms->con_per_no,
                    'contract_type' => $employee_phcms->contract_type,
                    'con_wef_date' => $employee_phcms->con_wef_date,
                    'con_wet_date' => $employee_phcms->con_wet_date,
                    'name' => $employee_phcms->name,
                    'nrc' => $employee_phcms->nrc,
                    'sex' => $employee_phcms->sex,
                    'mobile_no' => $employee_phcms->mobile_no,
                    'group_type' => $employee_phcms->group_type,
                    'job_title' => $employee_phcms->job_title,
                    'grade' => $employee_phcms->grade,


                    //'functional_section' => $employee_phcms->functional_section,
                    //'directorate' => $employee_phcms->directorate,
                    //'bu_code' => $employee_phcms->bu_code,
                    //'cc_code' => $employee_phcms->cc_code,
                    //'email' => $employee_phcms->staff_email,
                    'location' => $employee_phcms->location ?? $employee_phcms->functional_section,
                    'pay_point' => $employee_phcms->pay_point,
                    'job_code' => $employee_phcms->job_code ?? "--",
                    'station' => $employee_phcms->station ?? "--",
                    'affiliated_union' => $employee_phcms->affiliated_union ?? "--",

                    //'extension' => '',
                    //'sex' => $request->gender,
                    //'nrc' => $request->nrc,
                    //'contract_type',
                    //'two_fac_auth_status',
                    //'location',
                    //'profile_job_code',
                    //'profile_unit_code',
                    //'type_id',
                    //'pay_point',
                    //'job_code',
                    //'user_unit_code',
                    //'area_code'
                    //'user_unit_id',
                    //'positions_id',
                    //'user_region_id',
                    //'user_division_id',
                    //'user_directorate_id',
                    //'station',
                    //'last_login',
                    //'total_logins',
                    'guid' => Str::uuid()
                ],
            /*[

                'status_id' => $request->status_id,
            ]*/
            );

            if($request->has('user_profile') || !empty($request->get('user_profile'))){
                $employee->roles()->syncWithoutDetaching((int)$request->get('user_profile'));
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Employee has successfully been created..'
            ]);

        } catch (\Exception $exception) {
            Log::error($exception);
            $message = "User Failed to be created because the Staff number (" . $request->staff_number . ")
                could not be verified with PHCMS.";
            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }

    }

    public function show(User $user): Factory|View|Application
    {
        $roles = Role::all();
        return view('UserManagement.show')->with(compact('user', 'roles'));
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
    }


    public function search(Request $request): JsonResponse
    {

        try {
            $searchParam = $request->searchCriteria;
            $apiURL = 'http://dev.zesco.co.zm/ezesco_forms/public/api/users';
            //$apiURL = 'http://127.0.0.1:3001/ezesco_forms/public/api/users';
            $headers = [
                'Content-Type' => 'application/json',
            ];
            /*
             $dataset = PHCMSEmployee::select('*')
                ->where('con_per_no', $search)
                ->first();
            */
            $response = Http::withHeaders($headers)->get($apiURL, [
                'staff_number' => $searchParam,
            ]);

            return response()->json([
                'success' => true,
                'payload' => $response->json()
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'payload' => []
            ]);
        }
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

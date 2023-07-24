<?php

namespace App\Http\Controllers\UserManagement;

use _HumbugBoxbdf58a3ca165\Symfony\Component\Config\Definition\Exception\Exception;
use App\Constants\ErrorMessages;
use App\Exceptions\UserNotActiveException;
use App\Exceptions\UserOnBoardingException;
use App\Exceptions\UserUnitUpdateException;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Main\ActivityLogsController;
use App\Http\Requests\UserOnboardingRequest;
use App\Models\general\BusinessUnit;
use App\Models\general\CostCenter;
use App\Models\Main\ConfigWorkFlow;
use App\Models\reference\PHCMSEmployee;
use App\Models\Security\Role;
use App\Models\Security\User;
use App\Services\Security\ParameterEncryption;
use App\Services\Security\UserService;
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
use Illuminate\Support\Str;

class UsersController extends Controller
{

    public function index(): Factory|View|Application
    {
        $users = User::select('*')->get();
        return view('modules.userManagement.index')->with(compact('users'));
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
        $businessUnits = BusinessUnit::where('status', '=', '01')->get();
        $costCenters = CostCenter::where('status', '=', '01')->get();

        return view('modules.userManagement.addUser')
            ->with(compact('roles', 'businessUnits', 'costCenters'));
    }


    public function store(UserOnboardingRequest $request): JsonResponse
    {
        try {

            $validateWithHCMS = true;
            //will be profile assigned
            DB::beginTransaction();
            if ($validateWithHCMS) {
                try {
                    $employee_phcms = PHCMSEmployee::where('con_per_no', $request->staff_number)
                        ->where('con_st_code', '=', 'ACT')
                        ->first();
                    if (empty($employee_phcms)) {
                        throw new Exception("User Not Found");
                    }
                } catch (\Exception $ex) {
                    Log::error($ex);
                    throw new UserOnBoardingException(
                        "User Failed to be created because the Staff number (" . $request->staff_number . ") could not be verified with PHCMS.");
                }
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
                        // 'con_wef_date' => $employee_phcms->con_wef_date,
                        // 'con_wet_date' => $employee_phcms->con_wet_date,
                        'name' => $employee_phcms->name,
                        'nrc' => $employee_phcms->nrc,
                        // 'sex' => $employee_phcms->sex,
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
                        //'station' => $employee_phcms->station ?? "--",
                        //'affiliated_union' => $employee_phcms->affiliated_union ?? "--",
                        'area_code' => $request->get('business_area'),
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

                        //'user_unit_id',
                        //'positions_id',
                        //'user_region_id',
                        //'user_division_id',
                        //'user_directorate_id',
                        //'station',
                        //'last_login',
                        //'total_logins',
                        'guid' => Str::uuid()
                    ]
                );
            } else {
                $employee = User::firstOrCreate(
                    [
                        'staff_no' => $request->staff_number,
                    ],
                    [
                        'con_st_code' => StatusHelper::active(), // $request->user_status,
                        'password' => Hash::make($request->password),
                        'name' => $request->name,
                        'staff_no' => $request->staff_number,
                        'email' => $request->staff_email,
                        'username' => $request->login_name,
                        'phone' => $request->mobile_no,
                        'mobile_no' => $request->mobile_no,
                        'functional_section' => $request->user_unit,
                        'grade' => $request->grade,
                        'bu_code' => $request->business_unit_code,
                        'cc_code' => $request->cost_center_code,
                        'directorate' => $request->directorate,
                        'user_unit' => $request->user_unit,
                        'supervisor_code' => $request->staff_supervisorId,
                        'supervisor_name' => $request->staff_supervisor,
                        'job_title' => $request->job_title,
                        'guid' => Str::uuid(),
                        'area_code' => $request->get('business_area'),
                    ],
                );
            }

            if ($request->has('user_profile') || !empty($request->get('user_profile'))) {
                $employee->roles()->syncWithoutDetaching((int)$request->get('user_profile'));
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Employee has successfully been created..'
            ]);

        } catch (\Exception $ex) {
            Log::error($ex);
            $message = "User Failed to be created because of an error";
            if ($ex instanceof UserOnBoardingException) {
                $message = $ex->getMessage();
            }

            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }

    }

    public function show(User $user): Factory|View|Application
    {
        $user = User::where('id', '=', $user->id)->first();
        $roles = Role::all();
        return view('modules.userManagement.show')
            ->with(compact(
                'user',
                'roles'
            ));
    }


    public function edit($id)
    {
        //
    }

    public function attach(Request $request): RedirectResponse
    {
        try {
            $user = User::find($request->id);
            $user->roles()->syncWithoutDetaching($request->role_ids);
            return redirect()->back()->with('message', 'User Successfully Added To Selected Groups ..');
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->with('error', 'User Could Not Be Added To Groups..');
        }
    }

    public function detach(Request $request): RedirectResponse
    {
        $user = User::find($request->id);
        $user->roles()->detach($request->role_id);
        return redirect()->back()->with('message', 'Role Successfully detached..');
    }


    public function update(Request $request): void
    {
        $name = $request->input('stud_name');
        $id = $request->input('userId');

        DB::beginTransaction();

        echo "Record updated successfully.<br/>";
        echo '<a href = "/edit-records">Click Here</a> to go back.';

        $model = User::find($id);
        $model->name = $request->name;
        //$model->email = $request->email;
        // $model->extension = $request->extension;
        // $model->type_id = $request->user_type_id;
        //$model->job_code = $request->job_code ?? $model->job_code;
        User::where('id', $id)
            ->update(
                [
                    'name' => $name
                ]
            );

        DB::commit();

        // log the activity
        // ActivityLogsService::store($request, 'Updating of User', 'update', ' user updated', $model->staff_no);
        //return Redirect::back()->with('message', 'Details for ' . $model->name . ' have been Updated successfully');
    }


    public function destroy(User $user)
    {
    }

    public function search(Request $request): JsonResponse
    {
        try {

            $searchParam = strtoupper(trim($request->searchCriteria));

            if (str_starts_with($searchParam, 'C7') || str_starts_with($searchParam, '7')) {
                $dataset = PHCMSEmployee::select('*')
                    ->where('con_per_no', $searchParam)
                    ->where('con_st_code', '=', 'ACT')
                    ->whereNull('alt_per_no')
                    ->first();
            } else {
                $dataset = PHCMSEmployee::select('*')
                    ->where('name', 'LIKE', "%{$searchParam}%")
                    ->where('con_st_code', '=', 'ACT')
                    ->where(function ($query) {
                        $query->where('con_per_no', 'LIKE', "C7%")
                            ->orWhere('con_per_no', 'LIKE', "7%");
                    })
                    ->get();

            }

            if (empty($dataset)) {
                throw new UserNotActiveException(ErrorMessages::getMessage('err_0019'));
            }

            return response()->json([
                'success' => true,
                'payload' => $dataset
            ]);

        } catch (\Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_0012');

            if ($e instanceof UserNotActiveException) {
                $message = $e->getMessage();
            }

            return response()->json([
                'success' => false,
                'payload' => [],
                'message' => $message
            ]);
        }
    }

    public function profile(Request $request): View|\Illuminate\Foundation\Application|Factory|Application
    {
        if (empty($request->get('key'))) {
            return redirect(route('users.list'));
        }

        $id = (int)ParameterEncryption::decrypt($request->get('key'));
        $user = User::where('id', '=', $id)->first();
        $roles = Role::all();
        return view('modules.userManagement.show')
            ->with(compact('user', 'roles'));

    }

    public function sync(Request $request): JsonResponse
    {
        try {
            Log::info('User Data Update: User Id '. $request->userId);
            UserService::syncEmployeeFullDetails($request->userId);
            return response()->json([
                'state' => 'success',
                'message' => 'User Details Updated Successfully'
            ]);
        } catch (Exception $e) {
            $message = 'User Details Failed to Updated!';
            $errorInfo = $e->getMessage();
            Log::info('User Details Failed to Updated!. ERROR Message : ');
            Log::error($e);
            return response()->json([
                'state' => 'error',
                'error' => $message
            ]);
        }
    }
}

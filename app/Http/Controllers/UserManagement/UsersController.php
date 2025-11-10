<?php

namespace App\Http\Controllers\UserManagement;

use App\Constants\ErrorMessages;
use App\Constants\SystemMessages;
use App\Exceptions\UserNotActiveException;
use App\Exceptions\UserOnBoardingException;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserOnboardingRequest;
use App\Http\Requests\UserProfileUpdate;
use App\Http\Requests\UserSync;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\Security\Role;
use App\Models\Security\User;
use App\Services\Logging\ActivityLogsService;
use App\Services\Organization\StructureService;
use App\Services\Security\ProfileDelegationService;
use App\Services\Security\ProfileService;
use App\Services\Security\UserService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller {

    private readonly UserService $userService;
    private ProfileService $profileService;
    private ProfileDelegationService $profileDelegation;

    public function __construct(UserService              $userService,
                                ProfileService           $profileService,
                                ProfileDelegationService $profileDelegation) {
        $this->userService = $userService;
        $this->profileService = $profileService;
        $this->profileDelegation = $profileDelegation;
    }

    public function index(Request $request): Factory|View|Application {
        $users = User::when($request->search, function (Builder $query) use ($request) {
            $query->where('staff_no','like', '%' . strtoupper($request->search) . '%')
                ->orWhere('name', 'like', '%' . strtoupper($request->search) . '%');
        })->paginate(10);
        return view('modules.userManagement.index')
            ->with(compact('users','request'));
    }

    public function getCurrentUserDetails(): JsonResponse {
        return response()->json(array(
            'state' => 'success',
            'data' => Auth::user()
        ));
    }

    public function get(Request $request): JsonResponse {
        $ageSize = $request->input('pageSize');
        $users = User::paginate($ageSize);
        return response()->json(
            [
                'data' => $users,
                'itemsCount' => User::get()->count()
            ]
        );
    }

    public function create(): View {
        $roles = Role::get();
        $businessUnits = (new StructureService)->getBusinessUnits();
        $costCenters = (new StructureService)->getCostCenters();

        return view('modules.userManagement.addUser')
            ->with(compact(
                    'roles',
                    'businessUnits',
                    'costCenters'
                )
            );
    }

    public function store(UserOnboardingRequest $request): JsonResponse {
        try {

            $this->userService->createUser($request);

            return response()->json(
                FleetMasterJsonResponse::response(
                    'success',
                    true,
                    SystemMessages::USER_ONBOARDING_SUCCESS
                )
            );

        } catch (\Exception $ex) {
            Log::error($ex);
            $message = SystemMessages::USER_CREATION_FAILED;
            if ($ex instanceof UserOnBoardingException) {
                $message = $ex->getMessage();
            }

            return response()->json(
                FleetMasterJsonResponse::response(
                    'failure',
                    false,
                    $message
                )
            );
        }

    }

    public function show(Request $request, $id): Factory|View|Application {
        Log::debug("Showing Profile for $id");
        $this->verifyRequestSignature($request);

        $user = User::where('id', '=', $id)->first();
        $is_admin = auth()->user()->roles()->where('is_superuser', 1)->exists();
        $user_is_admin = $user->roles()->where('is_superuser', 1)->exists();
        $can_change = !$user_is_admin || $is_admin;

        $roles = Role::all();
        $passwordChangeOnly = false;
        $userDelegating = $this->profileDelegation->getDelegatedProfile($user->id);
        return view('modules.userManagement.show')
            ->with(compact(
                'user',
                'userDelegating',
                'passwordChangeOnly',
                'roles', 'can_change'
            ));
    }

    public function profile(Request $request): View|Factory|Application {
        $this->verifyRequestSignature($request);

        if (empty($request->get('key'))) {
            return redirect(route('users.list'));
        }

        $id = (int)$request->get('key');
        $user = User::where('id', '=', $id)->first();
        $isnt_admin = auth()->user()->roles()->where('is_superuser', '!=', 1)->doesntExist();
        $is_admin = $user->roles()->where('is_superuser', 1)->doesntExist();
        $can_change = !$isnt_admin && $is_admin;
        $roles = Role::when($isnt_admin, function ($query) {
            $query->where('is_superuser', '!=', 1);
        });
        $passwordChangeOnly = false;
        $userDelegating = $this->profileDelegation->getDelegatedProfile($user->id);
        return view('modules.userManagement.show')
            ->with(compact('user',
                'passwordChangeOnly',
                'userDelegating',
                'roles',
                'can_change'));

    }

    public function attach(Request $request): RedirectResponse {
        try {
            DB::beginTransaction();
            $this->profileService->assignProfile($request->id, $request->role_ids);
            DB::commit();
            return redirect()->back()->with('message', 'User Successfully Added To Selected Groups ..');
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->with('error', 'Role Could Not Be Assigned To User..');
        }
    }

    public function detach(Request $request): RedirectResponse {
        DB::beginTransaction();
        $this->profileService->revokeProfile($request->id, $request->role_ids);
        DB::commit();

        return redirect()->back()
            ->with('message',
                SystemMessages::roleAssignedSuccessful()
            );
    }

    public function update(UserProfileUpdate $request): JsonResponse {
        try {
            $this->userService->updateUserDetails($request);

            ActivityLogsService::store($request,
                'Updating of User',
                'update',
                ' user updated');

            return response()->json([
                'state' => 'success',
                'message' => SystemMessages::userUpdateSuccessful()
            ]);
        } catch (Exception $e) {
            $message = SystemMessages::userUpdateFailed();
            Log::debug($message);
            Log::error($e);

            return response()->json([
                'state' => 'error',
                'error' => $message
            ]);
        }
    }

    public function employeeSearch(Request $request): JsonResponse {
        try {

            $searchParam = strtoupper(trim($request->get('searchCriteria')));

            $dataset = $this->userService->searchEmployee($searchParam);

            return response()->json(
                FleetMasterJsonResponse::response(
                    '',
                    true,
                    '',
                    $dataset
                )
            );

        } catch (\Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_0012');

            if ($e instanceof UserNotActiveException) {
                $message = $e->getMessage();
            }

            return response()->json(
                FleetMasterJsonResponse::response(
                    '',
                    false,
                    $message,
                    []
                )
            );
        }
    }

    public function userSearch(Request $request): JsonResponse {
        try {
            $searchParam = strtoupper(trim($request->searchCriteria));

            $dataset = $this->userService->searchEmployee($searchParam);

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

    public function search(Request $request): JsonResponse {
        try {
            $searchParam = strtoupper(trim($request->searchCriteria));

            $dataset = $this->userService->search($searchParam);

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

    public function sync(UserSync $request): JsonResponse {
        try {
            Log::debug('User Data Update: User Id ' . $request->userId);
            UserService::syncEmployeeFullDetails($request->userId);
            return response()->json([
                'state' => 'success',
                'message' => SystemMessages::userUpdateSuccessful()
            ]);
        } catch (Exception $e) {
            $message = SystemMessages::userUpdateFailed();
            Log::debug($message);
            Log::error($e);
            return response()->json([
                'state' => 'error',
                'error' => $message
            ]);
        }
    }

    /**
     * @param Request $request
     * @return void
     */
    public function verifyRequestSignature(Request $request): void {
        if (!$request->hasValidSignature()) {
            abort(401);
        }
    }
}

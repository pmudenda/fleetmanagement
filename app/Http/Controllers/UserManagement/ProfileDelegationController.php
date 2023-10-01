<?php

namespace App\Http\Controllers\UserManagement;

use App\Constants\ErrorMessages;
use App\Constants\SystemMessages;
use App\Exceptions\ActiveUserDelegationException;
use App\Exceptions\UserNotActiveException;
use App\Http\Controllers\Controller;
use App\Http\Requests\DelegateProfile;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\Security\User;
use App\Services\Security\ProfileDelegationService;
use App\Services\Security\RoleService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfileDelegationController extends Controller
{

    private ProfileDelegationService $profileDelegation;

    public function __construct(ProfileDelegationService $profileDelegation)
    {
        $this->profileDelegation = $profileDelegation;
    }

    public function create(Request $request): View
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        $id = (int)$request->get('key');
        $user = User::where('id', '=', $id)->first();
        $selfDelegation = $request->get('self');

        $profiles = (new RoleService())->get();

        return view('modules.userManagement.profileDelegation')
            ->with(compact(
                'user',
                'profiles',
                'selfDelegation'
            ));
    }

    public function store(DelegateProfile $request): JsonResponse
    {
        try {
            Log::debug('Saving Profile Delegation');

            $this->profileDelegation->initiateDelegation($request);

            return response()->json(
                FleetMasterJsonResponse::response(
                    '',
                    true,
                    'User Profile Delegation Started Successfully',
                    []
                )
            );

        } catch (\Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_0012');

            if ($e instanceof UserNotActiveException
                || $e instanceof ActiveUserDelegationException) {
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

    public function cancel(Request $request): JsonResponse
    {
        try {
            Log::debug('Cancelling Profile Delegation');

            $this->profileDelegation->cancelDelegation($request);

            return response()->json(
                FleetMasterJsonResponse::response(
                    '',
                    true,
                    SystemMessages::DELEGATION_CANCELLED
                )
            );

        } catch (\Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_0012');

            if ($e instanceof UserNotActiveException
                || $e instanceof ActiveUserDelegationException) {
                $message = $e->getMessage();
            }

            return response()->json(
                FleetMasterJsonResponse::response(
                    '',
                    false,
                    $message
                )
            );
        }
    }

}

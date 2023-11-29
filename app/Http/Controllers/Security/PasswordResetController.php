<?php

namespace App\Http\Controllers\Security;

use App\Helpers\ConfigHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordResetRequest;
use App\Models\Security\User;
use App\Services\Security\ParameterEncryption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    public function __invoke(PasswordResetRequest $request): RedirectResponse
    {
        $userId = ParameterEncryption::decrypt($request->get('userId'));
        $user = User::where('id', '=', $userId)->first();

        $user->password = Hash::make($request->otp);
        $user->password_changed = ConfigHelper::passwordNotChanged();
        $user->save();

        // send emails here but use ques
        return redirect()->back()->with('message', 'User Password Reset Successful');

    }
}

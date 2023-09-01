<?php

namespace App\Http\Controllers\Security;

use App\Helpers\ConfigHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordResetRequest;
use App\Models\Security\User;
use App\Services\Security\ParameterEncryption;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    public function resetPassword(PasswordResetRequest $request)
    {
        $userId = ParameterEncryption::decrypt($request->get('userId'));
        $user = User::where('id', '=', $userId)->first();

        if (in_array($request->otp, ['Zesco123', 'zesco123', 'zesco@123', 'Zesco@123', 'Zesco12345', 'zesco12345'])) {
            return redirect()->back()->withInput()
                ->withErrors(
                    [
                        'otp' => 'Sorry your new password has been listed as too common
                        hence not so much secure.Please change to another password.'
                    ]);
        }

        $user->password = Hash::make($request->otp);
        $user->password_changed = ConfigHelper::passwordNotChanged();
        $user->save();

        // send emails here but use ques
        return redirect()->back()->with('message', 'User Password Reset Successful');

    }
}

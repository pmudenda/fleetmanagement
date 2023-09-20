<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Helpers\StatusHelper;
use App\Models\Security\User;
use App\Services\Security\UserService;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

const USERNAME_FIELD = 'username';
const USER_STATUS_FIELD = 'con_st_code';
const HAS_ACTIVE_SESSION_FIELD = 'has_active_session';
const USER_NAME_INPUT_FIELD = 'email';
const PASSWORD_INPUT_FIELD = 'password';
class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });

        Fortify::resetPasswordView(function ($request) {
            return view('auth.password.reset', ['request' => $request]);
        });

        Fortify::authenticateUsing(callback: function (Request $request) {

            $user = User::where(USERNAME_FIELD, $request->get(USER_NAME_INPUT_FIELD))
                ->where(USER_STATUS_FIELD, StatusHelper::activeUser())
                ->first();

            if ($user &&
                Hash::check($request->get(PASSWORD_INPUT_FIELD), $user->password)) {
                Auth::logoutOtherDevices($request->get(PASSWORD_INPUT_FIELD));
                $user->total_logins = ($user->total_logins ?? 0) + 1;
                $user->last_login = Carbon::now();
                UserService::logoutOtherDevices($user);
                $user->save();
                return $user;
            }

            return null;
        });

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $email = (string)$request->username;

            return Limit::perMinute(5)->by($email . $request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}

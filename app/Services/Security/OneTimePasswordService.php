<?php

namespace App\Services\Security;

use App\Models\UserCode;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class OneTimePasswordService
{
    /**
     * @throws Exception
     */
    public static function generateCode(bool $resend): void
    {
        //$oneTimePassword

        if ($resend) {
            self::generateOtp();
            return;
        }

        // first call to 2fa generate otp
        if (!Session::exists('user_otp')) {
            self::generateOtp();
            return;
        }

        $find = Session::get('user_otp');
        $date = Carbon::parse($find['created_at']);
        $now = Carbon::now();

        $diff = $date->diffInMinutes($now);

        // check if token is still valid before generating new one
        if ($diff >= (int)config('constant.otp_expiry')) {
            self::generateOtp();
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    public static function generateOtp(): void
    {
        if (Session::exists('user_2fa_code')) {
            Session::flash('user_2fa_code');
        }

        if (Session::exists('user_otp')) {
            Session::flash('user_otp');
        }

        $code = random_int(10000, 99999);

        $oneTimePassword = array(
            'user_id' => auth()->user()->id,
            'code' => Hash::make($code),
            'created_at' => Carbon::now()->toDateTimeString()
        );
        Session::put('user_otp', $oneTimePassword);

        Session::put('user_2fa_code', $code);

        $receiverNumber = auth()->user()->phone;
        $message = "Your+2FA+login+code+is+" . $code;

        try {
            $client = new Client();
            $request = new Request('GET', config('constants.sms_api')
                . $receiverNumber . '&text=' . $message);
            $client->sendAsync($request)->wait();
        } catch (\Exception $e) {
            info("Error: " . $e->getMessage());
        }
    }

}

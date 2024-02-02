<?php

namespace App\Services\Auth;

use App\Models\Auth\Otp;
use App\Models\Auth\User;
use Illuminate\Support\Carbon;

class OtpService
{
    public static function generateOTP(User $user, $type = Otp::VERFIY, int $time = 300)
    {
        if (self::isExpired($user)) {
            $user->otp()->delete();
            $data['token']     = random_int(111111, 999999);
            $data['expire_at'] = Carbon::now()->addSeconds(300)->getTimestamp();
            $data['type']      = $type;
            $otp               = $user->otp()->create($data);
            $user->refresh();
            return $otp;
        }

        return $user->otp;
    }

    public static function generateOtpWithUserId($user_id, $type = Otp::VERFIY, int $time = 300)
    {
        $user = User::findOrFail($user_id);
        self::generateOTP($user, $type, $time);
        $user->refresh();
        return $user->otp;
    }

    public static function generateOtpWithUserPhone($phone, $type = Otp::VERFIY, int $time = 300)
    {
        $user = User::where('phone', '=', $phone)->first();

        self::generateOTP($user, $type, $time);
        $user->refresh();
        return $user->otp;
    }

    public static function getOTP(User $user)
    {
        if (self::isExpired($user)) {
            return null;
        }
        return $user->otp;
    }

    public static function getOTPWithUserPhone($phone)
    {
        $user = User::where('phone', '=', $phone)->first();

        if (self::isExpired($user)) {
            return null;
        }
        return $user->otp;
    }

    public static function clearOTP(User $user)
    {
        $user->otp()->delete();
    }

    public static function isExpired(User $user): bool
    {
        if ( ! $user->otp) {
            return true;
        }
        return $user->otp->expired;
    }
}

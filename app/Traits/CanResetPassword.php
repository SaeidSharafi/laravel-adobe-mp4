<?php

namespace App\Traits;

use App\Notifications\Auth\OtpNotification;
use App\Notifications\Auth\ResetPasswordNotification;

trait CanResetPassword
{
    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getPhoneForPasswordReset()
    {
        return $this->phone;
    }

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $otp
     * @return void
     */
    public function sendPasswordResetNotification($otp)
    {
        $this->notify(new ResetPasswordNotification($otp));
    }

    /**
     * Send the password reset notification.
     *
     * @return void
     */
    public function sendPasswordResetOtpNotification()
    {
        $this->notify(new OtpNotification());
    }
}

<?php

namespace App\Auth;

interface CanResetPassword
{
    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getPhoneForPasswordReset();

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset();

    /**
     * Send the password reset notification.
     *
     * @param  string  $otp
     * @return void
     */
    public function sendPasswordResetNotification(string $otp);

    /**
     * Send the password reset notification.
     *
     * @return void
     */
    public function sendPasswordResetOtpNotification();
}

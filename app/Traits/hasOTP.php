<?php

namespace App\Traits;

use App\Models\Auth\Otp;

trait hasOTP
{
    public function otp()
    {
        return $this->hasOne(Otp::class);
    }
}

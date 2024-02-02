<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PasswordResetVerifyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => 'required_without:email|regex:/09[0-9]{9}/',
            'email' => 'required_without:phone|email',
            'otp'   => 'required|numeric|digits:6',
        ];
    }

    public function validateResetRequest()
    {
        $this->ensureIsNotRateLimited();

        $status = Password::validateOtp(
            $this->safe()->only(['phone', 'email']),
            $this->safe()['otp']
        );

        if (Password::INVALID_USER === $status) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'user' => [trans($status)],
            ]);
        }

        if (Password::INVALID_TOKEN === $status) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'otp' => [trans($status)],
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        return $status;
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if ( ! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('email'))
            . Str::lower($this->input('phone'))
            . '|' . $this->ip();
    }
}

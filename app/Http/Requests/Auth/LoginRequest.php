<?php

namespace App\Http\Requests\Auth;

use App\Models\Auth\User;
use App\Rules\EmailorPhoneRule;
use App\Services\Auth\OtpService;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
            'username' => ['required', 'string','max:255',new EmailorPhoneRule()],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return null|User
     *
     * @throws ValidationException
     */
    public function checkUsername(): null|User
    {
        $this->ensureIsNotRateLimited();

        $user = User::query()->whereEmailorPhone($this->username)->first();

        if ( ! $user) {
            RateLimiter::hit($this->throttleKey());

            if ( ! filter_var($this->username, FILTER_VALIDATE_EMAIL)
            && config('auth.allow_registration')) {
                $user = User::create(['phone' => $this->username]);
                $user->assignRole('user');
                return $user;
            }

            throw ValidationException::withMessages([
                'username' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        return $user;
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        $user = User::query()->with('otp')->whereEmailorPhone($this->username)->first();

        if ( ! $user) {
            throw ValidationException::withMessages([
                'password' => trans('auth.failed'),
            ]);
        }

        $field_type = filter_var($this->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if ( ! $this->otp && ! Auth::attempt([$field_type => $this->username, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'password' => trans('auth.failed'),
            ]);
        }

        if ($this->otp) {
            $this->authWithOTP($user);
        }

        RateLimiter::clear($this->throttleKey());
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
        return Str::lower($this->input('username')) . '|' . $this->ip();
    }

    protected function authWithOTP($user)
    {
        if ($user->otp->expired) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'otp' => trans('auth.otp_expired'),
            ]);
        }

        if ($this->otp !== $user->otp->token) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'otp' => trans('auth.otp_failed'),
            ]);
        }

        Auth::login($user, $this->remember);

        OtpService::clearOTP($user);
    }
}

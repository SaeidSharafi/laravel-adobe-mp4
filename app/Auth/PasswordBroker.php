<?php

namespace App\Auth;

use App\Auth\CanResetPassword as CanResetPasswordContract;
use App\Models\Auth\Otp;
use App\Services\Auth\OtpService;
use Closure;
use Illuminate\Contracts\Auth\PasswordBroker as PasswordBrokerContract;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Arr;
use UnexpectedValueException;

class PasswordBroker implements PasswordBrokerContract
{
    /**
     * The password token repository.
     *
     * @var TokenRepositoryInterface
     */
    protected $tokens;

    /**
     * The user provider implementation.
     *
     * @var UserProvider
     */
    protected $users;

    /**
     * Create a new password broker instance.
     *
     * @param  TokenRepositoryInterface  $tokens
     * @param  UserProvider  $users
     * @return void
     */
    public function __construct(TokenRepositoryInterface $tokens, UserProvider $users)
    {
        $this->users  = $users;
        $this->tokens = $tokens;
    }

    /**
     * Send a password reset link to a user.
     *
     * @param  array  $credentials
     * @param  Closure|null  $callback
     * @return string
     */
    public function sendResetLink(array $credentials, ?Closure $callback = null)
    {
        // First we will check to see if we found a user at the given credentials and
        // if we did not we will redirect back to this current URI with a piece of
        // "flash" data in the session to indicate to the developers the errors.
        $user = $this->getUser($credentials);

        if (null === $user) {
            return static::INVALID_USER;
        }


        $expire_time = (config('auth.passwords.' . config('auth.defaults.passwords') . '.expire') * 60);
        $otp         = OtpService::generateOtp($user, Otp::RESET, $expire_time * 60);
        if ($callback) {
            $callback($user, $otp);
        } else {
            // Once we have the reset token, we are ready to send the message out to this
            // user with a link to reset their password. We will then redirect back to
            // the current URI having nothing set in the session to indicate errors.

            if (array_key_exists('email', $credentials)) {
                $user->sendPasswordResetNotification($otp->token);
            } else {
                $user->sendPasswordResetOtpNotification();
            }
        }

        return static::RESET_LINK_SENT;
    }

    /**
     * Reset the password for the given token.
     *
     * @param  array  $credentials
     * @param  Closure  $callback
     * @return mixed
     */
    public function reset(array $credentials, Closure $callback)
    {
        $user = $this->validateReset($credentials);

        // If the responses from the validate method is not a user instance, we will
        // assume that it is a redirect and simply return it from this method and
        // the user is properly redirected having an error message on the post.
        if ( ! $user instanceof CanResetPasswordContract) {
            return $user;
        }

        $password = $credentials['password'];

        // Once the reset has been validated, we'll call the given callback with the
        // new password. This gives the user an opportunity to store the password
        // in their persistent storage. Then we'll delete the token and return.
        $callback($user, $password);

        $this->tokens->delete($user);

        return static::PASSWORD_RESET;
    }

    /**
     * Validate a password reset for the given credentials.
     *
     * @param  array  $credentials
     * @param  string  $otp
     *
     * @return \Illuminate\Contracts\Auth\CanResetPassword|string
     */
    public function validateOtp(array $credentials, $otp)
    {
        if (null === ($user = $this->getUser($credentials))) {
            return static::INVALID_USER;
        }

        if (OtpService::isExpired($user) || $user->otp->token !== $otp) {
            return static::INVALID_TOKEN;
        }

        return $this->tokens->create($user);
    }

    /**
     * Get the user for the given credentials.
     *
     * @param  array  $credentials
     * @return CanResetPasswordContract|null
     *
     * @throws UnexpectedValueException
     */
    public function getUser(array $credentials)
    {
        $credentials = Arr::except($credentials, ['token']);

        $user = $this->users->retrieveByCredentials($credentials);

        if ($user && ! $user instanceof CanResetPasswordContract) {
            throw new UnexpectedValueException('User must implement CanResetPassword interface.');
        }

        return $user;
    }

    /**
     * Create a new password reset token for the given user.
     *
     * @param  array  $credentials
     * @param  string  $token
     * @return string
     */
    public function getToken(array $credentials)
    {
        $credentials = Arr::except($credentials, ['token']);

        $user = $this->users->retrieveByCredentials($credentials);

        if ( ! $user instanceof CanResetPasswordContract) {
            return $user;
        }

        return $this->tokens->getToken($user);
    }

    /**
     * Create a new password reset token for the given user.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @return string
     */
    public function createToken(CanResetPasswordContract $user)
    {
        return $this->tokens->create($user);
    }

    /**
     * Delete password reset tokens of the given user.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @return void
     */
    public function deleteToken(CanResetPasswordContract $user)
    {
        $this->tokens->delete($user);
    }

    /**
     * Validate the given password reset token.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $token
     * @return bool
     */
    public function tokenExists(CanResetPasswordContract $user, $token)
    {
        return $this->tokens->exists($user, $token);
    }

    /**
     * Get the password reset token repository implementation.
     *
     * @return TokenRepositoryInterface
     */
    public function getRepository()
    {
        return $this->tokens;
    }

    /**
     * Validate a password reset for the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\CanResetPassword|string
     */
    protected function validateReset(array $credentials)
    {
        if (null === ($user = $this->getUser($credentials))) {
            return static::INVALID_USER;
        }

        if ( ! $this->tokens->exists($user, $credentials['token'])) {
            return static::INVALID_TOKEN;
        }

        return $user;
    }
}

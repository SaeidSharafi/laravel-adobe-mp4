<?php

namespace App\Auth;

use App\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DatabaseTokenRepository implements TokenRepositoryInterface
{
    /**
     * The database connection instance.
     *
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * The Hasher implementation.
     *
     * @var HasherContract
     */
    protected $hasher;

    /**
     * The token database table.
     *
     * @var string
     */
    protected $table;

    /**
     * The hashing key.
     *
     * @var string
     */
    protected $hashKey;

    /**
     * The number of seconds a token should last.
     *
     * @var int
     */
    protected $expires;

    /**
     * Minimum number of seconds before re-redefining the token.
     *
     * @var int
     */
    protected $throttle;

    /**
     * Create a new token repository instance.
     *
     * @param  ConnectionInterface  $connection
     * @param  HasherContract  $hasher
     * @param  string  $table
     * @param  string  $hashKey
     * @param  int  $expires
     * @param  int  $throttle
     *
     * @return void
     */
    public function __construct(
        ConnectionInterface $connection,
        HasherContract $hasher,
        $table,
        $hashKey,
        $expires = 60,
        $throttle = 60
    ) {
        $this->table      = $table;
        $this->hasher     = $hasher;
        $this->hashKey    = $hashKey;
        $this->expires    = $expires * 60;
        $this->connection = $connection;
        $this->throttle   = $throttle;
    }

    /**
     * Create a new token record.
     *
     * @param  CanResetPasswordContract  $user
     *
     * @return string
     */
    public function create(CanResetPasswordContract $user)
    {
        $email = $user->getEmailForPasswordReset();
        $phone = $user->getPhoneForPasswordReset();

        $this->deleteExisting($user);

        // We will create a new, random token for the user so that we can e-mail them
        // a safe link to the password reset form. Then we will insert a record in
        // the database so that we can verify the token within the actual reset.
        $token = $this->createNewToken();

        $this->getTable()->insert($this->getPayload($email, $phone, $token));

        return $token;
    }

    /**
     * Determine if a token record exists and is valid.
     *
     * @param  CanResetPasswordContract  $user
     * @param  string  $token
     *
     * @return bool
     */
    public function exists(CanResetPasswordContract $user, $token)
    {
        $record = (array) $this->getTable()
            ->where('email', $user->getEmailForPasswordReset())
            ->orWhere('phone', $user->getPhoneForPasswordReset())
            ->first();

        return $record
            && ! $this->tokenExpired($record['created_at'])
            && $this->hasher->check($token, $record['token']);
    }

    /**
     * get token.
     *
     * @param  CanResetPasswordContract  $user
     *
     * @return string
     */
    public function getToken(CanResetPasswordContract $user): string
    {
        $record = (array) $this->getTable()
            ->where('email', $user->getEmailForPasswordReset())
            ->orWhere('phone', $user->getPhoneForPasswordReset())
            ->first();

        return $this->hasher->make($record['token']);
    }

    /**
     * Determine if the given user recently created a password reset token.
     *
     * @param  CanResetPasswordContract  $user
     *
     * @return bool
     */
    public function recentlyCreatedToken(CanResetPasswordContract $user)
    {
        $record = (array) $this->getTable()
            ->where('email', $user->getEmailForPasswordReset())
            ->orWhere('phone', $user->getPhoneForPasswordReset())
            ->first();

        return $record && $this->tokenRecentlyCreated($record['created_at']);
    }

    /**
     * Delete a token record by user.
     *
     * @param  CanResetPasswordContract  $user
     *
     * @return void
     */
    public function delete(CanResetPasswordContract $user)
    {
        $this->deleteExisting($user);
    }

    /**
     * Delete expired tokens.
     *
     * @return void
     */
    public function deleteExpired()
    {
        $expiredAt = Carbon::now()->subSeconds($this->expires);

        $this->getTable()->where('created_at', '<', $expiredAt)->delete();
    }

    /**
     * Create a new token for the user.
     *
     * @return string
     */
    public function createNewToken()
    {
        return hash_hmac('sha256', Str::random(40), $this->hashKey);
    }

    /**
     * Get the database connection instance.
     *
     * @return ConnectionInterface
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Get the hasher instance.
     *
     * @return HasherContract
     */
    public function getHasher()
    {
        return $this->hasher;
    }

    /**
     * Delete all existing reset tokens from the database.
     *
     * @param  CanResetPasswordContract  $user
     *
     * @return int
     */
    protected function deleteExisting(CanResetPasswordContract $user)
    {
        return $this->getTable()
            ->where('email', $user->getEmailForPasswordReset())
            ->orWhere('phone', $user->getPhoneForPasswordReset())
            ->delete();
    }

    /**
     * Build the record payload for the table.
     *
     * @param  string  $email
     * @param  string  $token
     *
     * @return array
     */
    protected function getPayload($email, $phone, $token)
    {
        return [
            'email'      => $email,
            'phone'      => $phone,
            'token'      => $this->hasher->make($token),
            'created_at' => new Carbon()
        ];
    }

    /**
     * Determine if the token has expired.
     *
     * @param  string  $createdAt
     *
     * @return bool
     */
    protected function tokenExpired($createdAt)
    {
        return Carbon::parse($createdAt)->addSeconds($this->expires)->isPast();
    }

    /**
     * Determine if the token was recently created.
     *
     * @param  string  $createdAt
     *
     * @return bool
     */
    protected function tokenRecentlyCreated($createdAt)
    {
        if ($this->throttle <= 0) {
            return false;
        }

        return Carbon::parse($createdAt)->addSeconds(
            $this->throttle
        )->isFuture();
    }

    /**
     * Begin a new database query against the table.
     *
     * @return Builder
     */
    protected function getTable()
    {
        return $this->connection->table($this->table);
    }
}

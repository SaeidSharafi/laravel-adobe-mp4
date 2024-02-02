<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 *
 * @property bool $expired
 */
class Otp extends Model
{
    public const VERFIY   = 'verify';
    public const RESET    = 'reset';
    public const REGISTER = 'login';
    protected $table      = 'otp';

    protected $fillable = ['token','expire_at','type'];

    public function user(): BelongsTo
    {
        return $this->blongsTo(User::class);
    }
    public function remaining(): int
    {
        return $this->expire - time();
    }

    public function expired(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (($this->expire_at - Carbon::now()->getTimestamp()) <= 0)
        );
    }
}

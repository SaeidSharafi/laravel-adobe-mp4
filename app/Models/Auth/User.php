<?php

namespace App\Models\Auth;

use App\Auth\CanResetPassword as CanResetPasswordContract;
use App\Interfaces\SecureDeleteContract;
use App\Traits\CanResetPassword;
use App\Traits\hasOTP;
use App\Traits\SecureDelete;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract,
    SecureDeleteContract
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use HasApiTokens;
    use HasFactory;
    use hasOTP;
    use HasRoles;
    use LogsActivity;
    use MustVerifyEmail;
    use Notifiable;
    use SecureDelete;
    use SoftDeletes;

    /**
     * The relations that prevent deletion
     *
     */
    public const CONSTRAINS = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable
        = [
            'first_name',
            'last_name',
            'phone',
            'email',
            'password',
        ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden
        = [
            'password',
            'remember_token',
        ];

    protected $with = ['roles','latest_notifications'];

    protected $withCount = ['unreadNotifications'];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts
        = [
            'email_verified_at' => 'datetime',
        ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getAllowedOverrides(): array
    {
        return explode(",", $this->roles->pluck('allowoverride')->join(","));
    }

    public function otp(): HasOne
    {
        return $this->hasOne(Otp::class);
    }


    public function latest_notifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')->latest()->limit(5);
    }

    public function scopeWhereEmailorPhone($query, $username)
    {
        return $query
            ->where('email', '=', $username)
            ->orWhere('phone', '=', $username);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

    public function tapActivity(Activity $activity, string $event): void
    {
        /** @var Collection $properties */
        if ($properties = $activity->properties) {
            if ($properties->has('attributes')) {
                $attributes = $properties->get('attributes');
                if (isset($attributes['password'])) {
                    $attributes['password'] = '<secret>';
                }
                $properties->put('attributes', $attributes);
            }
            if ($properties->has('old')) {
                $old = $properties->get('old');
                if (isset($old['password'])) {
                    $old['password'] = '<secret>';
                }
                $properties->put('old', $old);
            }
            $activity->properties = $properties;
        }
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }
}

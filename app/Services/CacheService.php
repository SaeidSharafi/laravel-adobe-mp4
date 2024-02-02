<?php

namespace App\Services;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Opcodes\LogViewer\Facades\Cache;

class CacheService
{
    public static function forgetCaches(array $keys)
    {
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    public static function setCookie($name, $value, $duration): void
    {
        Session::put($name, $value);
        Cookie::queue(Cookie::make($name, $value, minutes: $duration));
    }
}

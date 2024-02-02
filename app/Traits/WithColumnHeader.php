<?php

namespace App\Traits;

trait WithColumnHeader
{
    public static function getColumns(): array
    {
        return collect(static::getHeaders())
            ->filter(fn ($item) => ! (isset($item['raw']) && $item['raw']))
            ->pluck('column')->toArray();
    }

    public static function getColumnsRaw(): string
    {
        return collect(static::getHeaders())->filter(fn ($item) => ! $item['raw'])->pluck('column')->join(',');
    }

    public static function getAllColumnsRaw(): string
    {
        return collect(static::getHeaders())->pluck('column')->join(',');
    }

    public static function getHeaderTitles(): array
    {
        return collect(static::getHeaders())
            ->filter(fn ($item) => ! (isset($item['hidden']) && $item['hidden']))
            ->pluck('title')->toArray();
    }
}

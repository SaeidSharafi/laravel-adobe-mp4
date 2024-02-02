<?php

namespace App\Interfaces;

interface WithColumns
{
    public static function getHeaders(): array;

    public static function getColumns(): array;

    public static function getColumnsRaw(): string;

    public static function getAllColumnsRaw(): string;

    public static function getHeaderTitles(): array;
}

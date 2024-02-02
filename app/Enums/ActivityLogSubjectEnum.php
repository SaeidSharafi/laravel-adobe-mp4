<?php

namespace App\Enums;

use App\Models\Auth\User;
use ValueError;

enum ActivityLogSubjectEnum: string
{
    case USER = User::class;

    public static function getValueLabel(): array
    {
        $pairs = [];

        foreach (self::cases() as $case) {
            $pairs[] = ['value' => $case->value, 'label' => $case->translate(), 'class' => $case->value];
        }
        return $pairs;
    }

    public static function getkeyValuePair(): array
    {
        $pairs = [];
        foreach (self::cases() as $case) {
            $pairs[$case->value] = $case->translate();
        }
        return $pairs;
    }
    public static function fromName(string $name): ActivityLogSubjectEnum
    {
        foreach (self::cases() as $status) {
            if ($name === $status->name) {
                return $status;
            }
        }
        throw new ValueError("{$name} is not a valid backing value for enum " . self::class);
    }


    public function translate(): string
    {
        return match ($this) {
            self::USER => trans('reports.log.subjects.user'),
        };
    }
}

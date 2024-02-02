<?php

namespace App\Enums;

enum YesNoEnum: int
{
    case YES = 1;
    case NO  = 0;

    public static function getValueLabel($none = false)
    {
        $pairs = [];
        if ($none) {
            $pairs[] = ['value' => 'none', 'label' => __('general.none')];
        }
        foreach (self::cases() as $case) {
            $pairs[] = ['value' => $case->value, 'label' => $case->translate()];
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

    public function translate($locale = null): string
    {
        return match ($this) {
            self::YES => trans('general.yes', locale: $locale),
            self::NO  => trans('general.no', locale: $locale),
        };
    }
}

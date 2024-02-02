<?php

namespace App\Enums;

enum GenderEnum: int
{
    case MALE   = 1;
    case FEMALE = 2;

    public static function fromFarsiTranslation($value)
    {
        if ('زن' === $value) {
            return GenderEnum::FEMALE;
        }

        return GenderEnum::MALE;
    }
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

    public function translate()
    {
        return match ($this) {
            self::MALE   => trans('general.gender.male'),
            self::FEMALE => trans('general.gender.female'),
        };
    }
}

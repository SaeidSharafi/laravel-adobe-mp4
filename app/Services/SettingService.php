<?php

namespace App\Services;

use App\Models\Settings\Setting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SettingService
{
    public function formatSettingForForm(Collection|\Illuminate\Support\Collection $settings)
    {
        $fields = [];
        foreach ($settings as $setting) {
            $fields[$setting->key] = $setting->value;
        }

        return $fields;
    }

    public function storeSetting($validatedRequests, $component)
    {
        $fields = [];
        foreach ($validatedRequests as $key => $value) {
            $fields[] = [
                'component' => $component,
                'key'       => $key,
                'value'     => $value,
            ];
        }

        Setting::upsert($fields,['component','key']);
    }
}

<?php

namespace App\Services\AdobeConnect;

use App\DTO\AdobeServerSettingDTO;
use App\Enums\SettingComponentEnum;
use App\Models\Settings\Setting;
use App\Services\SettingService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Opcodes\LogViewer\Facades\Cache;

class AdobeConnectSettingService
{
    private SettingService $settingService;
    public function __construct()
    {
        $this->settingService = app()->make(SettingService::class);
    }

    public function getSettings(): AdobeServerSettingDTO
    {
        return Cache::rememberForever('adobserver_setting', function () {
            $adobeServerSettings = Setting::query()->where('component', SettingComponentEnum::ADOBE_SERVER->value)
                ->get();
            $fields = $this->settingService->formatSettingForForm($adobeServerSettings);
            return new AdobeServerSettingDTO(
                data_get($fields,'server_address'),
                data_get($fields,'username'),
                data_get($fields,'password'),
            );
        });
    }

    public function getUrl()
    {
        return $this->getSettings()->address;
    }

    public function getDomain()
    {
        return Str::remove('https://', $this->getSettings()->address);
    }

    public function getApiUrl()
    {
        return $this->getSettings()->address.'/api/';
    }

    public function getRecordingsPath()
    {
        return Storage::disk('records')->path('recrodings');
    }
}

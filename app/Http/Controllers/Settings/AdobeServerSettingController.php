<?php

namespace App\Http\Controllers\Settings;

use App\DataTable\InertiaTableExtended;
use App\Enums\SettingComponentEnum;
use App\Helpers\PermissionKeys;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\AdobeServerRequest;
use App\Models\Settings\Setting;
use App\Services\AlertResponseService;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Activitylog\Models\Activity;

class AdobeServerSettingController extends Controller
{
    public function __construct(
        private readonly SettingService $service
    ) {

    }

    public function edit()
    {
        abort_if(!auth()->user()->can(PermissionKeys::SETTINGS_ADOBE_SERVER_VIEW), 403);
        $adobeServerSettings = Setting::query()->where('component', SettingComponentEnum::ADOBE_SERVER->value)->get();
        $adobeServerSettings = $this->service->formatSettingForForm($adobeServerSettings);
        return Inertia::render('Settings/AdobeServer', compact('adobeServerSettings'));
    }

    public function store(AdobeServerRequest $request)
    {
        abort_if(!auth()->user()->can(PermissionKeys::SETTINGS_ADOBE_SERVER_UPDATE), 403);

        $this->service->storeSetting($request->validated(), SettingComponentEnum::ADOBE_SERVER->value);

        return redirect()->back()->withStatus(AlertResponseService::success(__('response.update.success',
            ['model' => 'setting'])));
    }
}

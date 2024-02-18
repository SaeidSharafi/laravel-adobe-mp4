<?php

namespace App\Http\Controllers\AdobeConnect;

use App\DataTable\InertiaTableExtended;
use App\DTO\DataTableDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdobeConnectRecordingResource;
use App\Models\AdobeConnectRecording;
use App\Services\AdobeConnect\AdobeConnectSettingService;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;

class AdobeConnectRecordingController extends Controller
{
    public function index(AdobeConnectSettingService $settingService)
    {
        $recordings = QueryBuilder::for(AdobeConnectRecording::class)
            ->defaultSort('-datecreated')
            ->with('queue')
            ->paginate()
            ->through(fn ($x) => new AdobeConnectRecordingResource($x, $settingService))
            ->withQueryString();

        $barTableDTO = new DataTableDTO(
            resources: $recordings,
            title: __('adobe_connect.recordings'),
            icon: 'recording',
            exportRoute: null,
            backRoute: null,
        );
        return Inertia::render('DataTable/Index', $barTableDTO->toArray())
            ->exTable(function (InertiaTableExtended $table) {
                $table->column('scoid', label: __('adobe_connect.scoid'))
                    ->column('foldername', label: __('adobe_connect.foldername'))
                    ->column('url', label: __('adobe_connect.url'), isUrl: true)
                    ->column('datecreated', label: __('adobe_connect.datecreated'))
                    ->column('meetingurl', label: __('adobe_connect.meetingurl'), isUrl: true)
                    ->column('meetingname', label: __('adobe_connect.meetingname'))
                    ->column('recordingname', label: __('adobe_connect.recordingname'))
                    ->column('duration', label: __('adobe_connect.duration'));
            });
    }
}

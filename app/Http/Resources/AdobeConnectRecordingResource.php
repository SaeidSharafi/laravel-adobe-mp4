<?php

namespace App\Http\Resources;

use App\Services\AdobeConnect\AdobeConnectSettingService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/** @mixin \App\Models\AdobeConnectRecording */
class AdobeConnectRecordingResource extends JsonResource
{
    private $settingService;
    public function __construct($resource,AdobeConnectSettingService $settingService)
    {
        parent::__construct($resource);

        $this->settingService = $settingService;

    }
    public function toArray(Request $request): array
    {
        return [
            'scoid'         => $this->scoid,
            'foldername'    => $this->foldername,
            'url'           => $this->url,
            'datecreated'   => verta($this->datecreated)->format('Y-m-d H:i'),
            'meetingurl'    => $this->settingService->getUrl() .$this->meetingurl,
            'meetingname'   => $this->meetingname,
            'recordingname' => $this->recordingname,
            'duration'      => Str::padLeft(floor($this->duration / 3600), 2, '0') . ':'
                . Str::padLeft(floor($this->duration / 60) % 60, 2, '0'),
            'created_at'    => verta($this->created_at)->format('Y-m-d'),
            'updated_at'    => verta($this->updated_at)->format('Y-m-d'),

            'queue' => $this->relationLoaded('queue'),
        ];
    }
}

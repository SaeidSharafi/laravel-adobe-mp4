<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AdobeConnectRecording extends Model
{
    protected $fillable
        = [
            'scoid',
            'foldername',
            'url',
            'datecreated',
            'meetingurl',
            'meetingname',
            'recordingname',
            'duration',
        ];

    public function queue(): HasOne
    {
        return $this->hasOne(AdobeConnectRecordingQueue::class,'scoid');
    }
}

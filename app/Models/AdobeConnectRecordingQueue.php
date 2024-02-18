<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AdobeConnectRecordingQueue extends Model
{
    protected $fillable
        = [
            'scoid',
            'status',
            'datedownloaded',
            'datestarted',
        ];

    protected $casts
        = [
            'datedownloaded' => 'datetime',
            'datestarted'    => 'datetime',
        ];

    public function recording(): BelongsTo
    {
        return $this->belongsTo(AdobeConnectRecording::class, 'scoid');
    }
}

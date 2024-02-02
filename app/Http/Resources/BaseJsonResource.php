<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class BaseJsonResource extends JsonResource
{
    protected readonly bool $useLocale;

    public function __construct($resource, $useLocale = true)
    {
        parent::__construct($resource);
        $this->useLocale = $useLocale;
    }


    protected function formatDate(null|Carbon $date, string $format): ?string
    {
        if ( ! $date) {
            return null;
        }
        if ($this->useLocale) {
            return verta($date)->format($format);
        }

        return $date->format($format);
    }
}

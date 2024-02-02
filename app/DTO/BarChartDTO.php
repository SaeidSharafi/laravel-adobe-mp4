<?php

namespace App\DTO;

class BarChartDTO
{
    public function __construct(
        public readonly array $data,
        public readonly string $title,
        public readonly string $icon,
        public readonly string $exportRoute,
    ) {
    }


    public function toArray(): array
    {
        return [
            'data'        => $this->data,
            'title'       => $this->title,
            'icon'        => $this->icon,
            'exportRoute' => $this->exportRoute,
        ];
    }
}

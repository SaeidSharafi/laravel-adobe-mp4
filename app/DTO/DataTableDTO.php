<?php

namespace App\DTO;

class DataTableDTO
{
    public function __construct(
        public readonly array $resource,
        public readonly string $title,
        public readonly string $icon,
        public readonly string $exportRoute,
    ) {
    }

    public function toArray(): array
    {
        return [
            'resource'    => $this->resource,
            'title'       => $this->title,
            'icon'        => $this->icon,
            'exportRoute' => $this->exportRoute,
        ];
    }
}

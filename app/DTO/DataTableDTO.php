<?php

namespace App\DTO;

class DataTableDTO
{
    public function __construct(
        public readonly mixed $resources,
        public readonly string $title,
        public readonly string $icon,
        public readonly ?string $exportRoute,
        public readonly ?string $backRoute,
    ) {
    }


    public function toArray(): array
    {
        return [
            'resources'   => $this->resources,
            'title'       => $this->title,
            'icon'        => $this->icon,
            'exportRoute' => $this->exportRoute,
            'backRoute'   => $this->backRoute,
        ];
    }
}

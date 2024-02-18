<?php

namespace App\DataTable;

use Illuminate\Contracts\Support\Arrayable;

class Column implements Arrayable
{
    public function __construct(
        public string $key,
        public string $label,
        public string $translation,
        public bool $canBeHidden,
        public bool $hidden,
        public bool $sortable,
        public bool $selectable,
        public bool $isUrl,
        public bool|string $sorted
    ) {
    }

    public function toArray()
    {
        return [
            'key'           => $this->key,
            'label'         => $this->label,
            'translation'   => $this->translation,
            'can_be_hidden' => $this->canBeHidden,
            'hidden'        => $this->hidden,
            'sortable'      => $this->sortable,
            'selectable'    => $this->selectable,
            'isUrl'         => $this->isUrl,
            'sorted'        => $this->sorted,
        ];
    }
}

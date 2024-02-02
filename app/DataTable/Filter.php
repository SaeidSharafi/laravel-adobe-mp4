<?php

namespace App\DataTable;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class Filter implements Arrayable
{
    public function __construct(
        public string $key,
        public string $label,
        public array $options,
        public bool $noFilterOption,
        public bool $noFilterOptionKeyValue,
        public ?string $noFilterOptionLabel,
        public bool $canClear,
        public string $type,
        public ?array $dependsOn = null,
        public ?string $value = null,
        public ?string $route = null
    ) {
    }

    public function toArray()
    {
        $options = $this->options;

        if ($this->noFilterOption) {
            if ($this->noFilterOptionKeyValue) {
                $options = Arr::prepend(
                    $options,
                    ['value' => '', 'label' => $this->noFilterOptionLabel]
                );
            } else {
                $options = Arr::prepend($options, $this->noFilterOptionLabel, '');
            }
        }

        return [
            'key'        => $this->key,
            'label'      => $this->label,
            'options'    => $options,
            'value'      => $this->value,
            'can_clear'  => $this->canClear,
            'depends_on' => $this->dependsOn,
            'route'      => $this->route,
            'type'       => $this->type,
        ];
    }
}

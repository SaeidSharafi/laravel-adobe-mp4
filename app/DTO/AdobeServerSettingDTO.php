<?php

namespace App\DTO;

readonly class AdobeServerSettingDTO
{
    public function __construct(
        public ?string $address,
        public ?string $username,
        public ?string $password,
    )
    {

    }

}

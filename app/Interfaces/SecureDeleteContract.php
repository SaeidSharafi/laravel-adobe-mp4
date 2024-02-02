<?php

namespace App\Interfaces;

interface SecureDeleteContract
{
    public function secureSoftDelete(array $relations): void;

    public function secureDelete(array $relations, bool $forceDelete = false): void;

    public function delete();

    public function forceDelete();
}

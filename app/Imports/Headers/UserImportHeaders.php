<?php

namespace App\Imports\Headers;

use Illuminate\Support\Str;

class UserImportHeaders implements ImportHeader
{
    public readonly string $first_name;
    public readonly string $last_name;
    public readonly string $phone;
    public readonly string $email;
    public readonly string $password;
    public readonly string $is_admin;


    public function __construct()
    {
        // change fields name for CSV import
        // to match your own language or
        // use translations files
        $this->first_name              = Str::slug('first_name', '_');
        $this->last_name               = Str::slug('last_name', '_');
        $this->phone                   = Str::slug('phone', '_');
        $this->email                   = Str::slug('email', '_');
        $this->password                = Str::slug('password', '_');
        $this->is_admin                = Str::slug('is_admin', '_');


    }


}

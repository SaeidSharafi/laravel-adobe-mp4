<?php

namespace App\Imports;

use App\Imports\Headers\ImportHeader;
use App\Imports\Headers\UserImportHeaders;
use App\Rules\IranMobilePhoneRule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithValidation;

class UserImport implements
    ToCollection,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    SkipsEmptyRows,
    SkipsOnError,
    WithLimit
{
    use Importable;
    use RemembersRowNumber;
    use SkipsErrors;
    use SkipsFailures;

    public readonly ImportHeader $headers;


    private readonly Collection $insurance_types;
    private readonly Collection $introduction_types;
    private readonly Collection $doctors;
    private readonly Collection $departments;

    public function __construct(public readonly bool $write = false)
    {

        $this->headers = new UserImportHeaders();
    }

    public function collection(\Illuminate\Support\Collection $collection)
    {

        if ($this->write) {
            $users = $collection->map(fn ($item) => $this->createUser($item));

            DB::table('users')->insert($users->toArray());
        }
    }


    public function rules(): array
    {
        return [
            $this->headers->first_name => ['required', 'string'],
            $this->headers->last_name  => ['required', 'string'],
            $this->headers->phone      => ['required', new IranMobilePhoneRule()],
            $this->headers->email      => ['required', 'string', 'email'],
            $this->headers->password   => ['nullable'],
            $this->headers->is_admin   => ['required', 'boolean'],

        ];
    }

    public function customValidationAttributes()
    {
        return [
            $this->headers->first_name => __('auth.user.first_name'),
            $this->headers->last_name  => __('auth.user.last_name'),
            $this->headers->phone      => __('auth.user.phone'),
            $this->headers->email      => __('auth.user.email'),
            $this->headers->password   => __('auth.user.password'),
            $this->headers->is_admin   => __('auth.user.is_admin'),
        ];
    }

    public function limit(): int
    {
        return 500;
    }

    private function createUser($row)
    {

        $user['uuid']       = (string) Str::uuid();
        $user['first_name'] = $row[$this->headers->first_name];
        $user['last_name']  = $row[$this->headers->last_name];
        $user['phone']      = $row[$this->headers->phone];
        $user['email']      = $row[$this->headers->email];
        $user['password']   = $row[$this->headers->password];
        $user['is_admin']   = $row[$this->headers->is_admin];

        return $user;
    }

}

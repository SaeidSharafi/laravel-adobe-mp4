<?php

namespace App\Exports;

class UserExport extends BaseExport
{
    public static function getHeaders(): array
    {
        return [
            [
                'column' => 'first_name',
                'title'  => __('students.student.first_name'),
                'raw'    => false,
            ],
            [
                'column' => 'last_name',
                'title'  => __('students.student.last_name'),
                'raw'    => false,
            ],
            [
                'column' => 'phone',
                'title'  => __('students.student.phone'),
                'raw'    => false,
            ],
        ];
    }
}

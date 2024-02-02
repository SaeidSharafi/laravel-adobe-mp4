<?php

namespace App\Exports;

use App\Interfaces\WithColumns;
use App\Traits\WithColumnHeader;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BaseExport implements FromQuery, WithHeadings, WithColumns
{
    use Exportable;
    use WithColumnHeader;

    public function __construct(
        protected readonly Builder $query
    ) {
    }

    public static function getHeaders(): array
    {
        return [];
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return static::getHeaderTitles();
    }
}

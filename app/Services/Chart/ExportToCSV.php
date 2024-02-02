<?php

namespace App\Services\Chart;

use Illuminate\Support\Facades\Response;

class ExportToCSV
{
    public static function create(array $header, array $data)
    {
        $csvFileName = now()->format('Y_m_d_H_i') . '_export.csv';
        $headers     = [
            'Content-Type'        => 'text/csv;  charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
        ];
        echo "\xEF\xBB\xBF";
        $handle = fopen('php://output', 'w');
        fputcsv($handle, $header);
        foreach ($data as $item) {
            fputcsv($handle, [$item['name'], ...$item['data']]); // Add more fields as needed
        }

        fclose($handle);

        return Response::make('', 200, $headers);
    }
}

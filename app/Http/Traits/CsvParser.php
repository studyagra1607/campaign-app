<?php

namespace App\Http\Traits;

trait CsvParser
{
    public function CsvToArray($file, $getted = false)
    {
        if (! $getted) {
            $content = file_get_contents($file);
        } else {
            $content = $file;
        }
        $rows = array_map('str_getcsv', explode(PHP_EOL, $content));
        $rowKeys = array_shift($rows);
        $formattedData = [];
        foreach ($rows as $row) {
            if (count($row) == count($rowKeys)) {
                $associatedRowData = array_combine($rowKeys, $row);
                if (empty($keyField)) {
                    $formattedData[] = $associatedRowData;
                } else {
                    $formattedData[$associatedRowData[$keyField]] = $associatedRowData;
                }
            }
        }

        return $formattedData;
    }

    // public function CsvToArray($file)
    // {
    //     $csvString = file_get_contents($file);

    //     $lines = explode("\n", $csvString);

    //     $data = [];

    //     foreach ($lines as $line) {
    //         $data[] = explode(',', $line);
    //     }

    //     return $data;
    // }

    public function arrayToCsv($data)
    {
        $csvContent = '';

        foreach ($data as $row) {
            $csvContent .= '"'.implode('","', $row).'"'.PHP_EOL;
        }

        return $csvContent;
    }
}

<?php

namespace App\Http\Traits;

trait CsvParser {

    public function CsvToArray( $file )    {
        $content = file_get_contents($file);
        $rows = array_map('str_getcsv', explode(PHP_EOL, $content));
        $rowKeys = array_shift($rows);
        $formattedData = [];
        foreach ($rows as $row) {
            if( sizeof($row) == sizeof($rowKeys) )  {
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

    //     // Split the CSV string into lines
    //     $lines = explode("\n", $csvString);

    //     // Initialize an array to hold CSV data
    //     $data = [];

    //     // Loop through each line
    //     foreach ($lines as $line) {
    //         // Split each line by the comma (CSV delimiter)
    //         $data[] = explode(',', $line);
    //     }

    //     return $data;
        
    // }

    public function arrayToCsv($data)
    {
        // Initialize an empty string to store CSV content
        $csvContent = '';

        // Iterate through each row of data and concatenate it into CSV format
        foreach ($data as $row) {
            $csvContent .= '"'.implode('","', $row).'"'.PHP_EOL;
        }

        return $csvContent;
    }

}
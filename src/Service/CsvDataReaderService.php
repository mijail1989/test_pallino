<?php

namespace App\Service;

class CsvDataReaderService
{
    /**
     * Reads data from a CSV file and returns an associative array of the data.
     *
     * @param string $csvFilePath The path of the CSV file to read.
     * @return array An associative array containing the data from the CSV file, where each element represents a row of the file and contains an associative array of columns and values.
     */
    public function processFile(string $csvFilePath): array {
        $csvData = [];
        if (($handle = fopen($csvFilePath, "r")) !== false) {
            $columnNames = fgetcsv($handle);

            foreach ($columnNames as $columnName) {
                $columnName = mb_strtolower(trim($columnName));
            }
            while (($data = fgetcsv($handle)) !== false) {
                $rowData = [];
                foreach ($data as $key => $value) {
                    if (isset($columnNames[$key])) {
                        $rowData[$columnNames[$key]] = trim($value);
                    }
                }
                if (!empty($rowData)) {
                    $csvData[] = $rowData;
                }
            }
            fclose($handle);
        }
        return $csvData;
    }
}
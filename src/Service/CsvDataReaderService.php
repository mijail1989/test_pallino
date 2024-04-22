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
        // Opens the CSV file in read mode
        if (($handle = fopen($csvFilePath, "r")) !== false) {
            $columnNames = fgetcsv($handle);

            // Processes the header of the CSV file
            foreach ($columnNames as $columnName) {
                $columnName = mb_strtolower(trim($columnName));
            }
            // Reads the content of the CSV file
            while (($data = fgetcsv($handle)) !== false) {
                // Creates an associative array for each row
                $rowData = [];
                foreach ($data as $key => $value) {
                    // If the column name exists, add the value to the associative array
                    if (isset($columnNames[$key])) {
                        $rowData[$columnNames[$key]] = trim($value);
                    }
                }
                if (!empty($rowData)) {
                    // Adds the entire row only if it's not empty
                    $csvData[] = $rowData;
                }
            }
            fclose($handle);
        }
        return $csvData;
    }
}
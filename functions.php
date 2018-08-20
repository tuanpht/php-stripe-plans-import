<?php
/**
 * Read csv and return data as associated array
 *
 * @param string $csvFile CSV file path
 * @return generator CSV row data
 * @throws Exception
 */
function readCsv($csvFile)
{
    $handle = fopen($csvFile, 'r');

    if (!$handle) {
        throw new Exception('Cannot read csv file!');
    }

    $header = null;

    while (($row = fgetcsv($handle, 0, ',')) !== false) {
        if (!$header) {
            $header = $row;
        } else {
            yield array_combine($header, $row);
        }
    }

    fclose($handle);
}

/**
 * Build api params from csv data
 *
 * @param array $mappingFields
 * @param array $csvRow
 * @return array api params
 */
function mapCsvToApiFields($mappingFields, $csvRow)
{
    $data = [];
    foreach ($mappingFields as $csvField => $apiField) {
        if (isset($csvRow[$csvField]) && $csvRow[$csvField] != '') {
            $data[$apiField] = $csvRow[$csvField];
        }
    }

    return $data;
}

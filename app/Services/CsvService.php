<?php

namespace App\Services;

/**
 * Service for conversion data to CSV format
 * @package App\Services
 */
class CsvService
{

    /**
     * Temporary file
     *
     * @var
     */
    protected $file;

    /**
     * Convert table header and rows to csv string
     * @param array $columns table header (first row)
     * @param array $rows rows
     * @return string content for csv file
     * @throws \Exception
     */
    public function convert(array $columns, array $rows)
    {
        try {
            ob_start();
            $this->file = fopen('php://output', 'w');
            if ($columns) {
                $this->addRow($columns);
            }
            foreach ($rows as $row) {
                $this->addRow($row);
            }
            fclose($this->file);
            return ob_get_clean();
        } catch (\Exception $e) {
            ob_get_clean();
            throw $e;
        }

    }

    /**
     * Generate headers for response with csv file
     * @param string $out csv file content
     * @param string $filename new filename
     * @return array headers
     */
    public function headers(string $out, string $filename)
    {
        return [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename='.$filename,
            'Content-length' => strlen($out),
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ];
    }

    /**
     * @param $rowData
     */
    protected function addRow($rowData)
    {
        fputcsv($this->file,$rowData);
    }
}

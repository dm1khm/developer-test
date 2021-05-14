<?php

namespace Tests\Unit;

use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class for testing '/api/csv-export' request
 * @package Tests\Unit
 */
class CsvExportTest extends TestCase
{

    /**
     * Sample data
     * @var array
     */
    protected $requestData = [
        'columns' => [
            'first_name',
            'last_name',
            'emailAddress'
        ],
        'rows' => [
            [
                'John',
                'Doe',
                'john.doe@example.com'
            ],
            [
                'John2',
                'Doe2',
                'john.doe@example.com'
            ],
        ],
    ];

    /**
     * Test /api/csv-export for 200 OK
     * @return void
     */
    public function testResponseSuccess()
    {
        $response = $this->post('/api/csv-export', $this->requestData);
        $this->assertEquals($response->getStatusCode(), Response::HTTP_OK);
    }

    /**
     * Test /api/csv-export for 400
     * @return void
     */
    public function testResponseError()
    {

        $response = $this->post('/api/csv-export', []);
        $this->assertEquals($response->getStatusCode(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * Test if CSV file is valid
     * @return void
     */
    public function testCsvValid()
    {

        $response = $this->post('/api/csv-export', $this->requestData);
        $csvRows =  explode("\n",$response->getContent());
        $rows = [];
        foreach ($csvRows as $csvRow) {
            if($csvRow){
                $rows[] = str_getcsv($csvRow);
            }
        }
        $this->assertTrue(array_merge([$this->requestData['columns']],$this->requestData['rows']) == $rows);
    }
}

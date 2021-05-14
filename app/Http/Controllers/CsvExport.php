<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Csv;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CsvExport extends Controller
{

    /**
     * Converts the user input into a CSV file and streams the file back to the user
     * @param Request $request
     * @return mixed
     */
    public function convert(Request $request)
    {
        try {
            $request->validate([
                'columns' => 'required|array',
                'rows' => 'required|array',
            ]);
            $out = Csv::convert($request->get('columns'), $request->get('rows'));
            return response()->make($out, Response::HTTP_OK, Csv::headers($out,'export.csv'));
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

    }
}

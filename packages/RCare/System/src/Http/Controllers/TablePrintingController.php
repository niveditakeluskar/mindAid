<?php

namespace RCare\System\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class TablePrintingController extends Controller
{
    public function index(Request $request)
    {
        $name          = $request->input("name");
        $columns       = json_decode($request->input("columns"), True);
        $data          = json_decode($request->input("data"), True);
        $booleanFields = json_decode($request->input("boolean_fields"), True);
        return view("printing.table", [
            "name"          => $name,
            "columns"       => $columns,
            "data"          => $data,
            "booleanFields" => $booleanFields
        ]);
    }
}

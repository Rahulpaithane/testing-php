<?php

namespace App\Http\Controllers\api\query;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;

class ExecuteQuery extends Controller
{
    public function runQuery(Request $request){

        // Replace 'table_name' with the name of your table
        $tableName = 'students';
        $columnName = 'activeClassId';
        $afterColumn = 'address';

        // Run a raw SQL query to add a new column after an existing column in the table
        DB::statement("ALTER TABLE $tableName ADD $columnName int(11) NULL AFTER $afterColumn");

        return response()->json([
            'status' => true,
            'message' => 'Successfully',
        ], 200);


    }
}

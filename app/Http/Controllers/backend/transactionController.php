<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\StudentLedger;
use App\Models\Batch;
use DataTables;

class transactionController extends Controller
{
    public function studentTransactionController(Request $request){
        if($request->method() == 'GET'){
            // $batchData = StudentLedger::with('studentLedgerable','studentBasicDetail')
            // ->where('ledger_type', 'Credit')
            // ->get();
            // dd($batchData[0]->studentBasicDetail);
            // $batch=Batch::find(2);
            // dd($batch->studentLedgersdd);
            // return json_encode($batchData);
            return view('backend.transaction.transactionHistory', []);
        }

        $transactionList = StudentLedger::with('studentLedgerable', 'studentBasicDetail')
        ->where('ledger_type', 'Credit')
        ->orderBy('id', 'DESC');

        return DataTables::eloquent($transactionList)
        ->addIndexColumn()
        

        ->addColumn('profile_image', function($data){
            return 'a';
        })
        ->rawColumns(array('profile_image'))
        ->make(true);

    }
}

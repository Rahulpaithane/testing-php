<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\Batch;
use App\Models\ManageBook;
use App\Models\User;
use App\Models\QuestionBank;
use App\Models\Student;
use App\Models\StudentLedger;
use DataTables;

class DashboardController extends Controller
{
    public function dashboard(Request $request){
        // dd($request->routePath);
        $classes = ClassModel::all();
        $batches = Batch::all();
        $questions = QuestionBank::all();
        $teachers = User::where('role_type', 'Teacher')->get();
        $books = ManageBook::all();
        $students = Student::all();
        return view('backend.home.index', ['classes'=>$classes, 'students'=>$students, 'batches'=>$batches, 'questions'=>$questions, 'teachers'=>$teachers, 'books'=>$books]);
    }

    public function totalStudentInBatch(Request $request){
        $batchList = Batch::with('class')
            ->withCount('totalStudent')
            ->whereHas('class', function ($query) use ($request) {
                $query->where('prepration', $request->prepration);
        });

        return DataTables::eloquent($batchList)
        ->addIndexColumn()
        ->addColumn('batch_image', function($data){
            $url = asset($data->batch_image);
            $img='<a href="'.$url.'" target="_blank" class=""><img src="'.$url.'" width="40px" height="40px" class="img-rounded" align="center" alt="file" /></a>';
            return $img;
        })
        ->rawColumns(array('batch_image'))
        ->make(true);
    }

    public function latestTransactionHistory(Request $request){
        $latestTransaction = StudentLedger::with('studentBasicDetail')
        ->where('ledger_type', 'Credit')
        ->orderBy('id', 'DESC')
        ->limit(10);

        return DataTables::eloquent($latestTransaction)
        ->addIndexColumn()
        ->addColumn('batch_image', function($data){
            return 'as';
        })
        ->rawColumns(array('batch_image'))
        ->make(true);
    }
}

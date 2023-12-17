<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Chapter;
use App\Models\ClassModel;
use App\Models\Batch;
use App\Models\BatchCategory;
use App\Models\BatchSubCategory;
use App\Models\QuestionBank;
use App\Models\QuestionBankInfo;
use App\Models\ExamPaper;
use DataTables;
use Auth;
use DB;

class ExamPaperController extends Controller
{
    public function createPaper(Request $request){
        if($request->method()  == 'GET'){
            $classModel=ClassModel::where('status','1')->get();
            $subject=Subject::with('class')->where('status', '=', '1')->get();
            // $questionBank=QuestionBank::with('subject', 'chapter', 'questionBankInfo')->get();
            // $pra=json_decode($questionBank[1]->pragraph);
            // dd($questionBank[1]->questionBankInfo[0]['question']);
            return view('backend.examPaper.createPaper', ['classModel'=>$classModel, 'subject'=>$subject]);
        }

         $questionBank=QuestionBank::with('subject', 'chapter', 'questionBankInfo');
         !empty($request->subject) ? $questionBank->where('subject_id','=',$request->subject) : '';
         !empty($request->chapter) ? $questionBank->where('chapter_id','=',$request->chapter) : '';
         !empty($request->questionType) ? $questionBank->where('question_type','=',$request->questionType) : '';
         !empty($request->languageType) ? $questionBank->where('language_type','=',$request->languageType) : '';
         !empty($request->questionLevel) ? $questionBank->where('level','=',$request->questionLevel) : '';
        //  $questionBank->que;


        return DataTables::eloquent($questionBank)
            ->addIndexColumn()
            ->addColumn('inputCheck', function ($data) {
                $input='<input type="checkbox" class="checkOneRow" value="'.$data->id.'">';
                return $input;
            })
            // ->addColumn('status', function ($data) {
            //     if ($data->status == '1') {
            //         $status = '<a href="javascript:void(0);" class="toggleStatus alert alert-success" style="text-decoration:none;" data="' . route('admin.classStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >Active</a>';
            //     } else {
            //         $status = '<a href="javascript:void(0);" class="toggleStatus alert alert-danger" style="text-decoration:none;" data="' . route('admin.classStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >In-Active</a>';
            //     }
            //     return $status;
            // })
            // ->addColumn('action', function($data){
            //     $button='<center>';
            //     // $button .= '<a href="javascript:void(0);" onClick="editClass(' . htmlentities(json_encode($data)) . ')" class="edit_employee" data="' . route('admin.manageClasses') . '" id="' . $data->id . '"><i class="fas fa-edit" style="font-size:24px"></i></a>';
            //     // $button.='&#160;&#160;&#160;&#160;';
            //     $button.='  <a href="javascript:void(0);" class="delete2023" url="'.route('admin.classesDelete').'" id="'.$data->id.'"  ><i class="fas fa-trash-alt" style="font-size:20px;color:black"></i></a>';
            //     $button.='</center>';
            //     return $button;
            // })


            ->addColumn('paragraphInfo', function($data){
                $pra =json_decode($data->pragraph);
                $q=json_decode($data->questionBankInfo[0]['question']);
                $l=$data->language_type;
                // $englishData = strip_tags($pra->English);
                // $englishData =$data->question_type =='Group' ? json_encode($pra->English) : '';
                $englishData = $data->question_type =='Group' ? ($l =='Both' ? $pra->English : $pra->$l) : ($l =='Both' ? $q->English : $q->$l) ;
                return $englishData;
            })

            ->addColumn('lang', function($data){
                $button='<center>';
                
                $button.=$data->language_type == 'Both' ? 'English, Hindi' : $data->language_type;
                $button.='</center>';
                return $button;
            })
       
            ->rawColumns(array( "lang", "inputCheck", "paragraphInfo", "status",  "action"))
            ->make(true);
    }

    public function insertPaper(Request $request){
        $paper = new ExamPaper();
        $paper->batch_id =$request->batch;
        $paper->batch_category_id=$request->paperCategory;
        isset($request->paperSubCategory) ? 
        $paper->batch_sub_category_id=$request->paperSubCategory 
        : '';
        $paper->paper_name=$request->paperName;
        $paper->is_live=$request->isLive;
        $paper->language_type =$request->languageType;
        $paper->total_question=$request->total_question;
        $paper->total_duration=$request->time_duration;
        $paper->total_marks=$request->totalMarks;
        $paper->question_id=$request->quetionIdsArr;
        $paper->exam_date=$request->exam_sheduled_date;
        $paper->exam_time=$request->exam_sheduled_time;
        $paper->per_question_no=$request->perQuestionNo;
        $paper->negative_marking_type=$request->negativeMarkingType;
        $paper->per_negative_no=$request->perNegativeNo;
        $paper->added_by=Auth::guard('admin')->user()->id;
        $paper->save();


        return response()->json(["statuscode" => '201', "message" => 'Paper Created Successfully!', "actionType"=>"001"]);
    }

    public function managePaper(Request $request){ 
        if($request->method()  == 'GET'){
            $classModel=ClassModel::where('status','1')->get();
            // $questionBank = ExamPaper::with('batch', 'examCategory', 'examSubCategory')
            // ->with(['batch.class' => function ($query) {
            //     $query->select('id', 'name'); // You can select only the necessary fields from the class table
            // }])
            // ->get();
            // dd($questionBank);
            // $pra=json_decode($questionBank[1]->pragraph);
            // dd($questionBank[1]->questionBankInfo[0]['question']);
            return view('backend.examPaper.managePaper', ['classModel'=>$classModel]);
        }

        $paper=ExamPaper::with('batch', 'batch.class', 'examCategory', 'examSubCategory');
        return DataTables::eloquent($paper)
            ->addIndexColumn()

            ->addColumn('status', function ($data) use($request) {
                if ($data->status == '1') {
                    $status = '<a href="javascript:void(0);" class="toggleStatus alert alert-success" style="text-decoration:none;" data="' . route($request->routePath.'.examPaperStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >Active</a>';
                } else {
                    $status = '<a href="javascript:void(0);" class="toggleStatus alert alert-danger" style="text-decoration:none;" data="' . route($request->routePath.'.examPaperStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >In-Active</a>';
                }
                return $status;
            })
            ->addColumn('action', function($data) use($request) {
                $button='<center>';
                $button .= '<a href="'.route($request->routePath.'.viewPaper',['id'=>$data->id]).'" target="_blank"  ><i class="fas fa-eye" style="font-size:24px"></i></a>';
                $button.='&#160;&#160;&#160;&#160;';
                $button.='  <a href="javascript:void(0);" class="delete2023" url="'.route($request->routePath.'.examPaperDelete').'" id="'.$data->id.'"  ><i class="fas fa-trash-alt" style="font-size:20px;color:black"></i></a>';
                $button.='</center>';
                return $button;
            })

            ->addColumn('action2', function($data) use($request) {
                $button='<center>';
              
                $button.='  <a href="javascript:void(0);" class="convertPPTPDF" url="'.route($request->routePath.'.examPaperDelete').'" id="'.$data->id.'"  >Convert PDF/PPT</a>';
                $button.='</center>';
                return $button;
            })

       
            ->rawColumns(array("status",  "action", "action2"))
            ->make(true);
    }

    public function examPaperStatus(Request $request){
        $record = ExamPaper::find($request->id);
        $record->status = $request->status;
        $record->save();
        return response()->json(["statuscode" => '001', "message" => 'Status Updated Successfully!!' ]);
     }

     public function examPaperDelete(Request $request){
        try{
            $exam = ExamPaper::find($request->id);
            $exam->delete();

            DB::commit();
            return response()->json(["statuscode" => '001', "message" => 'Record Deleted Successfully!!' ]);
        }catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return response()->json(["statuscode" => '000', 'errors' => $e->getMessage()], 500);
        }
    }

    public function fetchClassToBatch(Request $request){
        $data=Batch::where('status', '1')->where('class_id', '=', $request->id)->get();

        return response()->json(["statuscode" => '201', "message" => 'Successfully!', "batch"=>$data ]);
    }

    public function fetchClassToSubject(Request $request){
        $data=Subject::where('status', '1')->where('class_id', '=', $request->id)->get();

        return response()->json(["statuscode" => '201', "message" => 'Successfully!', "subject"=>$data ]);
    }

    public function fetchBatchToPaperCategory(Request $request){
        $data=BatchCategory::where('status', '1')->where('batch_id', '=', $request->id)->get();

        return response()->json(["statuscode" => '201', "message" => 'Successfully!', "paperCategory"=>$data ]);
    }

    public function fetchPaperCategoryToPaperSubCategory(Request $request){
        $data=BatchSubCategory::where('status', '1')->where('batch_category_id', '=', $request->id)->get();

        return response()->json(["statuscode" => '201', "message" => 'Successfully!', "paperSubCategory"=>$data ]);
    }
}

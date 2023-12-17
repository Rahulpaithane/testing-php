<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OmrPaper;
use App\Models\Subject;
use App\Models\ClassModel;
use App\Models\OmrPaperAnswer;
use DB;
use Auth;
use DataTables;
class OmrDigitalController extends Controller
{
    //NEW MANUAL OMR PAPERS
    public function newDigitalPaper(Request $request){
        if($request->method() == 'GET'){
            $classModel=ClassModel::where('status','1')->get();
            return view('backend.omrDigital.newPaper', ['classModel'=>$classModel]);
        }

        try{
            DB::beginTransaction();

            $paper = new OmrPaper();
            $paper->omr_type = 'Manual';
            $paper->omr_code = rand(1000,9999).rand(1000,9999);
            $paper->paper_name=$request->paperName;
            $paper->option_format=$request->optionFormat;
            // $paper->batch_id = $request->batch;
            isset($request->batch_id) ?
            $paper->batch_id=$request->batch_id
            : '';
            $paper->exam_date=$request->exam_sheduled_date;
            $paper->exam_time=$request->exam_sheduled_time;
            $paper->total_question=$request->totalQuestion;
            $paper->total_marks=$request->totalMarks;
            $paper->numberPerQuestion=$request->numberPerQuestion;
            $paper->examDuration=$request->totalDuration;
            $paper->isNegative=$request->isNegativeMarking;
            $paper->numberPerNegative=$request->numberPerNegative;
            $paper->status = 0;
            $paper->added_by=Auth::guard('admin')->user()->id;
            $paper->save();

            for($i=1; $i <= intval($request->totalQuestion); $i++){
                $ans='answer_'.$i;
                $answer = new OmrPaperAnswer();
                $answer->omr_paper_id=$paper->id;
                $answer->omr_answer=$request->$ans;
                $answer->added_by=Auth::guard('admin')->user()->id;
                $answer->save();
            }

            DB::commit();

            return response()->json(["statuscode" => '201', "message" => 'Digital Paper Added Successfully!', "actionType"=>"001",]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['statuscode'=>'500', 'message'=>'Internal server error.. please contact to the Administrator!', 'errors'=>$e->getMessage()]);
        }
    }

    //COLLECTION OMR PAPERS
    public function newOmrCollectionPaper(Request $request){
        if($request->method() == 'GET'){
            $classModel=ClassModel::where('status','1')->get();
            $subject=Subject::with('class')->where('status', '=', '1')->get();

            return view('backend.omrDigital.createOmrCollectionPaper', ['classModel'=>$classModel, 'subject'=>$subject]);
        }

        try{
            DB::beginTransaction();
            $omr_code = rand(1000,9999).rand(1000,9999);
            $paper = new OmrPaper();
            $paper->omr_type = 'Collection';
            $paper->omr_code = $omr_code;

            isset($request->batch_id) ?
            $paper->batch_id=$request->batch_id
            : '';
        
            isset($request->paperCategory) ?
            $paper->batch_category_id=$request->paperCategory
            : '';
            isset($request->paperSubCategory) ?
            $paper->batch_sub_category_id=$request->paperSubCategory
            : '';
            $paper->paper_name=$request->paperName;
            $paper->question_id=$request->quetionIdsArr;
            $paper->option_format='Alphabetical';
            $paper->total_question=$request->total_question;
            $paper->numberPerQuestion=$request->perQuestionNo;
            $paper->total_marks=$request->totalMarks;
            $paper->exam_date=$request->exam_sheduled_date;
            $paper->exam_time=$request->exam_sheduled_time;
            $paper->examDuration=$request->time_duration;
            $paper->isNegative=$request->negativeMarkingType;
            $paper->numberPerNegative=$request->perNegativeNo;
            $paper->added_by=Auth::guard('admin')->user()->id;
            $paper->status = 1;
            $paper->save();
            DB::commit();

            return response()->json(["statuscode" => '201', "message" => 'Digital OMR Collection Paper Added Successfully!', "actionType"=>"001",]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['statuscode'=>'500', 'message'=>'Internal server error.. please contact to the Administrator!', 'errors'=>$e->getMessage()]);
        }
    }

    public function digitalPaperList(Request $request){
        if($request->method() == 'GET'){
            $omrPaper = OmrPaper::get();
            // dd($omrPaper);
            return view('backend.omrDigital.omrList', []);
        }

            $omrPaper = OmrPaper::query()->where('omr_type', 'Manual');
            return DataTables::eloquent($omrPaper)
                ->addIndexColumn()
                ->addColumn('status', function ($data) use($request) {
                    if ($data->status == '1') {
                        $status = '<a href="javascript:void(0);" class="toggleStatus alert alert-success" style="text-decoration:none;" data="' . route($request->routePath.'.digitalPaperStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >Active</a>';
                    } else {
                        $status = '<a href="javascript:void(0);" class="toggleStatus alert alert-danger" style="text-decoration:none;" data="' . route($request->routePath.'.digitalPaperStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >In-Active</a>';
                    }
                    return $status;
                })
                ->addColumn('action', function($data) use($request) {
                    $button='<center>';
                    // $button .= '<a href="javascript:void(0);" onClick="editSubject(' . htmlentities(json_encode($data)) . ')" class="edit_batch" data="' . route('admin.manageSubject') . '" id="' . $data->id . '"><i class="fas fa-edit bg-light" style="font-size:20px; color:green;"></i></a>';
                    // $button.='&#160;&#160;&#160;&#160;';
                    $button.='  <a href="javascript:void(0);" class="delete2023" url="'.route($request->routePath.'.digitalPaperDelete').'" id="'.$data->id.'"  ><i class="fas fa-trash-alt bg-light" style="font-size:20px;color:red;"></i></a>';
                    $button.='</center>';
                    return $button;
                })


                ->rawColumns(['status', 'action'])
                ->make(true);
    }

    public function collectionOmrList(Request $request){

        // $paper = OmrPaper::where('omr_type', 'Collection')->with('batch');
        $paper = OmrPaper::where('omr_type', 'Collection');
        return DataTables::eloquent($paper)
            ->addIndexColumn()
            ->addColumn('status', function ($data) use($request) {
                if ($data->status == '1') {
                    $status = '<a href="javascript:void(0);" class="toggleStatus alert alert-success" style="text-decoration:none;" data="' . route($request->routePath.'.digitalPaperStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >Active</a>';
                } else {
                    $status = '<a href="javascript:void(0);" class="toggleStatus alert alert-danger" style="text-decoration:none;" data="' . route($request->routePath.'.digitalPaperStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >In-Active</a>';
                }
                return $status;
            })

            ->addColumn('action', function($data) use($request){
                $button='<center>';
                $button .= '<a href="'.route($request->routePath.'.viewOmrPaper',['id'=>$data->id]).'" target="_blank"  ><i class="fas fa-eye bg-secondary" style="font-size:24px; color:blue"></i></a>';
                $button.='&#160;&#160;&#160;&#160;';
                $button.='  <a href="javascript:void(0);" class="delete2023" url="'.route($request->routePath.'.digitalPaperDelete').'" id="'.$data->id.'"  ><i class="fas fa-trash-alt  bg-light" style="font-size:20px;color:red"></i></a>';
                $button.='</center>';
                return $button;
            })

            ->rawColumns(array("status",  "action"))
            ->make(true);
    }

    public function digitalPaperStatus(Request $request){
        $record = OmrPaper::find($request->id);
        $record->status = $request->status;
        $record->save();
        return response()->json(["statuscode" => '001', "message" => 'Status Updated Successfully!!' ]);
     }

     public function digitalPaperDelete(Request $request){
        try{
            $book = OmrPaper::find($request->id);
            $book->delete();

            DB::commit();
            return response()->json(["statuscode" => '001', "message" => 'Record Deleted Successfully!!' ]);
        }catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return response()->json(["statuscode" => '000', 'errors' => $e->getMessage()], 500);
        }
    }
}

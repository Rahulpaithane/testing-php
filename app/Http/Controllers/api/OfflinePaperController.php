<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentLedger;
use App\Models\OmrPaper;
use App\Models\OmrPaperAnswer;
use App\Models\omrPaperResult;
use App\Models\QuestionBank;
use App\Models\QuestionBankInfo;
use Auth;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OfflinePaperController extends Controller
{
    public function digitalPaperList(Request $request){

        $validator = Validator::make($request->all(), 
            [ 
            'batchId' => 'required', 
        ]); 

        if ($validator->fails()) {  
            return response()->json(['error'=>$validator->errors()], 401); 
        }

        if(StudentLedger::where('student_id', Auth::guard('api')->user()->id)
        ->where('status', '1')->where('student_ledgerable_type', 'App\Models\Batch')
        ->where('student_ledgerable_id', $request->batchId)
        ->first()){

            $digitalPaperList=OmrPaper::select('id', 'omr_type', 'omr_code', 'batch_id', 'paper_name', 'total_question', 'total_marks', 'exam_date', 'exam_time', 'examDuration', 'isNegative')->where('batch_id', $request->batchId)->where('status','1')->get();

            return response()->json([
                'status' => true,
                'message' => 'Successfully',
                'digitalPaperList' =>$digitalPaperList,
            ], 200);
            
        } else {
            return response()->json(['status'=>false, 'message'=>'Invalid Batch'], 401); 
        }

    }

    public function digitalPaperQuestion(Request $request){

        $validator = Validator::make($request->all(), 
            [ 
            'digital_paper_id' => 'required', 
        ]); 

        if ($validator->fails()) {  
            return response()->json(['error'=>$validator->errors()], 401); 
        }

        $digitalPaperInfo=OmrPaper::where('id', $request->digital_paper_id)->where('status','1')->first();

            if(!$digitalPaperInfo){
                return response()->json(['status'=>false, 'message'=>'Invalid Digital Paper'], 401); 
            }

            if($digitalPaperInfo->omr_type=='Collection'){
                $digitalQuestionList=[];
                $questionId=json_decode($digitalPaperInfo->question_id);
                foreach($questionId as $idd){
                    $digitalQuestionList[]=['id'=>strval($idd), 'attemptAnswer'=>''];
                }
            } else {
                $manualList=OmrPaperAnswer::where('omr_paper_id', $request->digital_paper_id)
                ->where('status', '1')
                ->select('id', DB::raw("'' as attemptAnswer"))
                ->get();
                $digitalQuestionList=[];
                foreach($manualList as $idd){
                    $digitalQuestionList[]=['id'=>strval($idd->id), 'attemptAnswer'=>''];
                }
            }

            

            return response()->json([
                'status' => true,
                'message' => 'Successfully',
                'digitalQuestion' =>[
                    'digital_paper_id' => $request->digital_paper_id,
                    'batch_id' =>$digitalPaperInfo->batch_id,
                    'omr_type' => $digitalPaperInfo->omr_type,
                    'paper_name' => $digitalPaperInfo->paper_name,
                    'option_format' => $digitalPaperInfo->option_format,
                    'total_question' => $digitalPaperInfo->total_question,
                    'total_marks' => $digitalPaperInfo->total_marks,
                    'numberPerQuestion' => $digitalPaperInfo->numberPerQuestion,
                    'examDate' => $digitalPaperInfo->exam_date,
                    'examTime' => $digitalPaperInfo->exam_time,
                    'examDuration' => $digitalPaperInfo->examDuration,
                    'isNegative' => $digitalPaperInfo->isNegative,
                    'numberPerNegative' => $digitalPaperInfo->numberPerNegative,
                    'question'=>$digitalQuestionList 
                ],
            ], 200);
            
    }

    public function submitDigitalPaper(Request $request){
        $validator = Validator::make($request->all(),
            [
            // 'batchId' => 'required',
            'digital_paper_id' => 'required',

        ]);

        if ($validator->fails()) {

            return response()->json(['error'=>$validator->errors()], 401);

        }

        $digitalPaperInfo=OmrPaper::where('id', $request->digital_paper_id)->where('status','1')->first();
        $attemptQuestion=[];
        $totalCorrect=0;
        $totalWrong=0;
        $totalBlank=0;
        if($digitalPaperInfo->omr_type=='Collection'){

            foreach($request->question as $qInfo){

                $questionBank=QuestionBank::with('questionBankInfo')->where('id', $qInfo['id'])->first();

                $correctAnswer=$questionBank->questionBankInfo[0]->answer;

                if($qInfo['attemptAnswer'] == $correctAnswer){
                    $isCorrect='Right';
                    $totalCorrect+=1;
                } else {
                    if($qInfo['attemptAnswer'] ==''){
                        $isCorrect='Skipped';
                        $totalBlank+=1;
                    } else {
                        $isCorrect='Wrong';
                        $totalWrong+=1;
                    }
                }

                $attemptQuestion[]=[
                    'id'=>$qInfo['id'],
                    'attemptAnswer'=>$qInfo['attemptAnswer'],
                    'correctAnswer'=>$correctAnswer,
                    'isCorrect'=>$isCorrect
                ];
            }


            
        } else {

            foreach($request->question as $qInfo){

                $questionBank=OmrPaperAnswer::where('id', $qInfo['id'])->where('omr_paper_id', $request->digital_paper_id)->first();

                $correctAnswer=$questionBank->omr_answer;

                if($qInfo['attemptAnswer'] == $correctAnswer){
                    $isCorrect='Right';
                    $totalCorrect+=1;
                } else {
                    if($qInfo['attemptAnswer'] ==''){
                        $isCorrect='Skipped';
                        $totalBlank+=1;
                    } else {
                        $isCorrect='Wrong';
                        $totalWrong+=1;
                    }
                }

                $attemptQuestion[]=[
                    'id'=>$qInfo['id'],
                    'attemptAnswer'=>$qInfo['attemptAnswer'],
                    'correctAnswer'=>$correctAnswer,
                    'isCorrect'=>$isCorrect
                ];
            }

        }

        if($digitalPaperInfo->isNegative == 'Yes'){
            $correctMarks=intval($totalCorrect) * floatval($digitalPaperInfo->numberPerQuestion);
            $wrongMarks=intval($totalWrong) * floatval($digitalPaperInfo->numberPerNegative);
            $totalMarks=floatval($correctMarks) - floatval($wrongMarks);
        } else {
            $totalMarks=intval($totalCorrect) * floatval($digitalPaperInfo->numberPerQuestion);
        }

        $result = new omrPaperResult();
        $result->student_id = Auth::guard('api')->user()->id;
       
        $result->omr_paper_id =$request->digital_paper_id;
        $result->paper_name =$digitalPaperInfo->paper_name;
        $result->attemptDate =$request->attemptDate;
        $result->total_question =$digitalPaperInfo->total_question;
        $result->totalDurationTime =$digitalPaperInfo->examDuration;
        $result->attemptDurationTime =$request->attemptDurationTime;
        $result->totalCorrect =intVal($totalCorrect);
        $result->totalWrong =intVal($totalWrong);
        $result->totalSkipped =intVal($totalBlank);
        $result->attemptTotalMarks=$totalMarks;
        $result->rank =0;
        $result->accuracy = $totalCorrect !=0 ? floatVal($totalCorrect) / ( floatVal($totalCorrect) + floatVal($totalWrong) ) * 100 : 0;
        $result->average =0;
        $result->totalSavedTime ='0';
        $result->attemptQuestionDetail=json_encode($attemptQuestion);
        $result->save();

        return response()->json([
            'status' => true,
            'message' => 'Successfully',
        ], 200);
    }

    public function resultDigitalPaper(Request $request){

        $validator = Validator::make($request->all(),
            [
            // 'batchId' => 'required',
            'digital_paper_id' => 'required',

        ]);

        if ($validator->fails()) {

            return response()->json(['error'=>$validator->errors()], 401);

        }

        // $totalStudent = omrPaperResult::->where('batch_id', $request->batchId)
        //     ->where('omr_paper_id', $request->digital_paper_id)
        //     ->count();

        $Record = omrPaperResult::where('student_id', Auth::guard('api')->user()->id)
            // ->where('batch_id', $request->batchId)
            ->where('omr_paper_id', $request->digital_paper_id)
            ->select('paper_name', 'attemptDate', 'total_question', 'totalDurationTime', 'attemptDurationTime', 'totalCorrect', 'totalWrong', 'totalSkipped', 'attemptTotalMarks', 'accuracy', 'average', DB::raw('"" as cuttoff'))
            ->selectRaw('(
                SELECT COUNT(*) + 1 
                FROM omr_paper_results AS qr 
                WHERE qr.attemptTotalMarks > omr_paper_results.attemptTotalMarks
                AND qr.omr_paper_id = ?
            ) AS `rank`', [$request->digital_paper_id])
            ->first();

            // $cuttOff= floatval($attemptTotalMarks)/intVal($totalStudent);

        return response()->json([
            'status' => true,
            'message' => 'Successfully',
            'result' => $Record
        ], 200);
    }

    public function searchOmrPaper(Request $request){

        $validator = Validator::make($request->all(), 
            [ 
            'omr_code' => 'required', 
        ]); 

        if ($validator->fails()) {  
            return response()->json(['error'=>$validator->errors()], 401); 
        }

        $digitalPaperList=OmrPaper::select('omr_papers.id', 'omr_papers.omr_type', 'omr_papers.omr_code', 'omr_papers.batch_id', 'omr_papers.paper_name', 'omr_papers.total_question', 'omr_papers.total_marks', 'omr_papers.exam_date', 'omr_papers.exam_time', 'omr_papers.examDuration', 'omr_papers.isNegative')
        ->where('omr_code', $request->omr_code)
        ->where('status',1)
        ->first();

        if($digitalPaperList){
            return response()->json([
                'status' => true,
                'message' => 'Successfully',
                'digitalPaperList' =>$digitalPaperList,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid OMR Code',
            ], 404);
        }
    }


    public function attemptPaperList(Request $request){



            $digitalPaperList=OmrPaper::select('omr_papers.id', 'omr_papers.omr_type', 'omr_papers.omr_code', 'omr_papers.batch_id', 'omr_papers.paper_name', 'omr_papers.total_question', 'omr_papers.total_marks', 'omr_papers.exam_date', 'omr_papers.exam_time', 'omr_papers.examDuration', 'omr_papers.isNegative')
            ->join('omr_paper_results', 'omr_papers.id', 'omr_paper_results.omr_paper_id')
            ->where('omr_paper_results.student_id', Auth::guard('api')->user()->id)
            ->get();

            return response()->json([
                'status' => true,
                'message' => 'Successfully',
                'digitalPaperList' =>$digitalPaperList,
            ], 200);
            

    }


}

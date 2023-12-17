<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
use Validator;
use Carbon\Carbon;
use App\Models\Student;
use App\Models\ClassModel;
use App\Models\QuestionBank;
use App\Models\Batch;
use App\Models\ExamPaper;
use App\Models\StudentLedger;
use App\Models\BatchCategory;
use App\Models\govtResult;
use App\Models\govtResultAttemptQuestion;
use App\Models\PreviousYearQuestion;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\DB;


class PracticePaperController extends Controller
{


    public function practiceSetList(Request $request){
        $validator = Validator::make($request->all(),
            [
            'batchId' => 'required',
            'is_live' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json(['error'=>$validator->errors()], 401);

        }

        // $data=Batch::with('purchase', 'examPaper')
        // ->select('id', 'class_id', 'name', 'batch_price', 'batch_offer_price', 'batch_type')
        // ->where('status', '1')
        // ->where('id', $request->batchId)
        // ->get();


        $data = Batch::with(['examPaper' => function ($query) use ($request) {
            $query->where('is_live', $request->is_live);
        }])
        ->select('id', 'class_id', 'name', 'batch_price', 'batch_offer_price', 'batch_type')
        ->where('status', '1')
        ->where('id', $request->batchId)
        ->get();

        return response()->json([
            'status' => true,
            'message' => 'Successfully',
            'batch' =>$data,
        ], 200);
    }

    public function practiceListCategory(Request $request){
        $validator = Validator::make($request->all(),
            [
            'batchId' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json(['error'=>$validator->errors()], 401);

        }

        $batch = Batch::select('id', 'class_id', 'name', 'batch_price', 'batch_offer_price', 'batch_type')
        ->where('status', '1')
        ->where('id', $request->batchId)->first();

        $batchCategory = BatchCategory::with('batchSubCategory')
        ->select('id', 'category_name')
        ->where('status', '1')
        ->where('batch_id', $request->batchId)
        ->get();

        $additionalObject = (object) ['id' => 0, 'category_name' => 'All'];

            // Add the additional object at the beginning of the collection
            $batchCategory->prepend($additionalObject);


        return response()->json([
            'status' => true,
            'message' => 'Successfully',
            'batch' =>$batch,
            'category' =>$batchCategory,
        ], 200);
    }

    public function latestPracticeList(Request $request){
        $validator = Validator::make($request->all(),
            [
            'batchId' => 'required',
            'is_live' => 'required',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json(['message'=>$validator->errors()], 401);
        }

        $batchCategory = ExamPaper::
        select('id', 'batch_id', 'batch_category_id', 'batch_sub_category_id', 'paper_name', 'is_live', 'language_type', 'total_question', 'total_duration', 'total_marks', 'per_question_no', 'negative_marking_type', 'per_negative_no', 'exam_date', 'exam_time' )
        ->where('batch_id', $request->batchId);
        if($request->category_id !='0'){
            $batchCategory->where('batch_category_id', $request->category_id);
        }
        
        $batchCategory->where('is_live', $request->is_live)
        ->where('status', '1');
        if (isset($request->sub_category_id) && ($request->sub_category_id != '' )) {
            $batchCategory->where('batch_sub_category_id', $request->sub_category_id);
        }

        $result=$batchCategory->get();
     

        if($result){
            return response()->json([
                'status' => true,
                'message' => 'Successfully',
                'list' =>$result,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Set Not Available',
                'list' =>$results,
            ], 401);
        }


    }

   public function attemptPracticeSet(Request $request){
        $validator = Validator::make($request->all(),
            [
            'classId' => 'required',
            'batchId' => 'required',
            'practiceSetId' => 'required',
            'attemptStatus' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json(['error'=>$validator->errors()], 401);

        }

        if($request->attemptStatus == 'Submit'){
            return response()->json([
                'status' => false,
                'message' => 'Already Attempted Exam',
            ], 401);
        }

        // if()
        $paper=ExamPaper::where('batch_id', $request->batchId)
        ->where('id', $request->practiceSetId)
        ->where('status', 1)
        ->with('attemptedResult.questionInfo')
        ->first();

        // return response()->json([
        //     'status' => true,
        //     'message' => 'Successfully',
        //     'data'=>$paper,
        // ], 200);
        if($paper){
            // $question=QuestionBank::whereIn('id',  json_decode($paper->question_id) )
            // ->with('questionBankInfo')
            // ->select('id', 'question_type', 'language_type', 'pragraph')
            // ->selectSub(function ($query) {
            //     $query->select('exam', 'year')
            //         ->from('previous_year_questions')
            //         ->whereJsonContains('questionBankInfoId', DB::raw('question_banks.id'));
            //         // ->whereNull('previous_year_questions.deleted_at');
            //         // ->limit(1);
            // }, 'tag')
            // ->get();

            $question = QuestionBank::whereIn('id', json_decode($paper->question_id))
            ->with('questionBankInfo')
            ->select('id', 'question_type', 'language_type', 'pragraph')
            ->get();

            $question=QuestionBank::whereIn('id',  json_decode($paper->question_id) )
            ->with('questionBankInfo')
            ->select('id', 'question_type', 'language_type', 'pragraph')
            ->get();

            $quest=[];

            foreach ($question as $q) {
                $previousQuestionData = PreviousYearQuestion::whereJsonContains('questionBankInfoId', $q->id)
                    ->select('exam', 'year')
                    ->get();

                    $groupedData = $previousQuestionData->groupBy('exam')->map(function ($group) {
                        $years = $group->pluck('year')->unique()->implode(', ');
                        return $group->first()->exam . " - [" . $years . "]";
                    });
                    
                    // $tag=$previousQuestionData[0]->exam;
                    $quest[]=[
                        'id' => $q->id,
                        'question_type' => $q->question_type,
                        'language_type' => $q->language_type,
                        'pragraph' => $q->pragraph,
                        'tag' =>$groupedData->implode(', '), //$previousQuestionData == true ? $previousQuestionData->exam."-[".$previousQuestionData->year."]" : "",
                        'question_bank_info' => $q->questionBankInfo,
                        
                    ];
            }



            $instruction=[
                'classId' =>intVal($request->classId),
                'batchId' =>intVal($request->batchId),
                'practiceSetId' =>intVal($request->practiceSetId),
                'paper_name' => $paper->paper_name,
                'language_type' => $paper->language_type,
                'total_question' => $paper->total_question,
                'total_duration' => $paper->total_duration,
                'total_marks' => $paper->total_marks,
                'exam_time' => $paper->exam_time,
                'per_correct_question_no' => $paper->per_question_no,
                'negative_marking_type' => $paper->negative_marking_type,
                'per_negative_no' => $paper->per_negative_no,

            ];

            return response()->json([
                'status' => true,
                'message' => 'Successfully',
                'instruction'=>$instruction,
                'attemptedResult' => $paper->attemptedResult,
                'question' =>$question,
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'data not found!',
            ], 404);
        }

    }

    public function attemptPracticeSett(Request $request){
        $validator = Validator::make($request->all(),
            [
            'classId' => 'required',
            'batchId' => 'required',
            'practiceSetId' => 'required',
            'attemptStatus' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json(['error'=>$validator->errors()], 401);

        }

        if($request->attemptStatus == 'Submit'){
            return response()->json([
                'status' => false,
                'message' => 'Already Attempted Exam',
            ], 401);
        }

        // if()
        $paper=ExamPaper::where('batch_id', $request->batchId)
        ->where('id', $request->practiceSetId)
        ->where('status', 1)
        ->with('attemptedResult.questionInfo')
        ->first();

        // return response()->json([
        //     'status' => true,
        //     'message' => 'Successfully',
        //     'data'=>$paper,
        // ], 200);
        if($paper){
            // $question=QuestionBank::whereIn('id',  json_decode($paper->question_id) )
            // ->with('questionBankInfo')
            // ->select('id', 'question_type', 'language_type', 'pragraph')
            // ->selectSub(function ($query) {
            //     $query->select('exam', 'year')
            //         ->from('previous_year_questions')
            //         ->whereJsonContains('questionBankInfoId', DB::raw('question_banks.id'));
            //         // ->whereNull('previous_year_questions.deleted_at');
            //         // ->limit(1);
            // }, 'tag')
            // ->get();

            // $question = QuestionBank::whereIn('id', json_decode($paper->question_id))
            // ->with('questionBankInfo')
            // ->select('id', 'question_type', 'language_type', 'pragraph')
            // ->get();

            $question=QuestionBank::whereIn('id',  json_decode($paper->question_id) )
            ->with('questionBankInfo')
            ->select('id', 'question_type', 'language_type', 'pragraph')
            ->get();

            $quest=[];

            foreach ($question as $q) {
                $previousQuestionData = PreviousYearQuestion::whereJsonContains('questionBankInfoId', $q->id)
                    ->select('exam', 'year')
                    ->get();

                    $groupedData = $previousQuestionData->groupBy('exam')->map(function ($group) {
                        $years = $group->pluck('year')->unique()->implode(', ');
                        return $group->first()->exam . " - [" . $years . "]";
                    });
                    
                    // $tag=$previousQuestionData[0]->exam;
                    $quest[]=[
                        'id' => $q->id,
                        'question_type' => $q->question_type,
                        'language_type' => $q->language_type,
                        'pragraph' => $q->pragraph,
                        'tag' =>$groupedData->implode(', '), //$previousQuestionData == true ? $previousQuestionData->exam."-[".$previousQuestionData->year."]" : "",
                        'question_bank_info' => $q->questionBankInfo,
                        
                    ];
            }



            $instruction=[
                'classId' =>intVal($request->classId),
                'batchId' =>intVal($request->batchId),
                'practiceSetId' =>intVal($request->practiceSetId),
                'paper_name' => $paper->paper_name,
                'language_type' => $paper->language_type,
                'total_question' => $paper->total_question,
                'total_duration' => $paper->total_duration,
                'total_marks' => $paper->total_marks,
                'exam_time' => $paper->exam_time,
                'per_correct_question_no' => $paper->per_question_no,
                'negative_marking_type' => $paper->negative_marking_type,
                'per_negative_no' => $paper->per_negative_no,

            ];

            return response()->json([
                'status' => true,
                'message' => 'Successfully',
                'instruction'=>$instruction,
                'attemptedResult' => $paper->attemptedResult,
                'question' =>$quest,
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'data not found!',
            ], 404);
        }

    }

   public function submitPracticeSet(Request $request){
        $validator = Validator::make($request->all(),
            [
            'classId' => 'required',
            'batchId' => 'required',
            'practiceSetId' => 'required',

        ]);

        if ($validator->fails()) {

            return response()->json(['error'=>$validator->errors()], 401);

        }


        $govtResult=govtResult::where('student_id', Auth::guard('api')->user()->id)
        ->where('class_id', $request->classId)
        ->where('batch_id', $request->batchId)
        ->where('exam_paper_id', $request->practiceSetId)
        ->first();

        if($govtResult){
            $result=$govtResult;
            // return $result->id;

            govtResultAttemptQuestion::where('govt_result_id', intval($result->id))->delete();

        } else {
            $result = new govtResult();
        }


        $result->student_id = Auth::guard('api')->user()->id;
        $result->class_id = $request->classId;
        $result->batch_id = $request->batchId;
        $result->exam_paper_id =$request->practiceSetId;
        $result->paper_name =$request->paper_name;
        $result->attemptDate =$request->attemptDate;
        $result->total_question =$request->total_question;
        $result->totalDurationTime =$request->total_duration;
        $result->attemptDurationTime =$request->attemptDurationTime;
        $result->totalCorrect =0;
        $result->totalWrong =0;
        $result->totalSkipped =0;
        $result->totalMarks=0;
        $result->rank =0;
        $result->accuracy =0;
        $result->average =0;
        $result->totalSavedTime ='0';
        $result->submitionType =$request->submitionType =='Pause' ? 0 : 1;
        $result->save();

        $totalCorrect=0;
        $totalWrong=0;
        $totalBlank=0;

        foreach ($request->question as $key => $value) {
            if($value['attemptType'] =='Blank' || $value['attemptType'] =='Not Attempt'){

                $isCorrect='Blank';
                $totalBlank+=1;
                $value['attemptAnswer']='Not Attempt';
                

            } else {
                if($value['attemptAnswer'] ==''){
                    $isCorrect='Blank';
                    $totalBlank+=1;
                    $value['attemptAnswer']='Not Attempt';
                } else{
                    if($value['attemptAnswer'] == $value['correctAnswer']){
                        $isCorrect='Yes';
                        $totalCorrect+=1;
                    } else {
                        $isCorrect='No';
                        $totalWrong+=1;
                    }
                }
                
            }

            $answer = new govtResultAttemptQuestion();
            $answer->govt_result_id = $result->id;
            $answer->exam_paper_id =$request['practiceSetId'];
            $answer->question_type = $value['question_type'];
            $answer->language_type = $value['language_type'];
            $answer->question_bank_id =$value['question_bank_id'];
            $answer->sub_question_id =$value['question_bank_info_id'];
            $answer->attemptAnswer =$value['attemptAnswer'];
            $answer->attemptTime =$value['attemptTime'];
            $answer->attemptType =$value['attemptType'];
            $answer->correctAnswer =$value['correctAnswer'];
            $answer->isAnswerCorrect =$isCorrect;
            $answer->save();
        }

        if($request->negative_marking_type == 'Yes'){
            $correctMarks=intval($totalCorrect) * floatval($request->per_correct_question_no);
            $wrongMarks=intval($totalWrong) * floatval($request->per_negative_no);
            $totalMarks=floatval($correctMarks) - floatval($wrongMarks);
        } else {
            $totalMarks=intval($totalCorrect) * floatval($request->per_correct_question_no);
        }

        if($totalCorrect !=0){
            $accuracy=floatVal($totalCorrect) / ( floatVal($totalCorrect) + floatVal($totalWrong) ) * 100;
        } else {
            $accuracy=0;
        }

        

        govtResult::where('id', $result->id)
        ->update([
            'totalCorrect' => $totalCorrect,
            'totalWrong' => $totalWrong,
            'totalSkipped' => $totalBlank,
            'totalMarks'=>$totalMarks,
            'accuracy'=>number_format($accuracy,2),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Successfully',
        ], 200);

    }

    public function resultPracticePaper(Request $request){

        $validator = Validator::make($request->all(),
            [
            'classId' => 'required',
            'batchId' => 'required',
            'exam_paper_id' => 'required',

        ]);

        if ($validator->fails()) {

            return response()->json(['error'=>$validator->errors()], 401);

        }

        $Record = govtResult::where('student_id', Auth::guard('api')->user()->id)
            ->where('class_id', $request->classId)
            ->where('batch_id', $request->batchId)
            ->where('exam_paper_id', $request->exam_paper_id)
            ->select('*', DB::raw('"" as cuttoff'))
            ->selectRaw('(
                SELECT COUNT(*) + 1 
                FROM govt_results AS qr 
                WHERE qr.totalMarks > govt_results.totalMarks
                AND qr.class_id = ?
                AND qr.batch_id = ?
                AND qr.exam_paper_id = ?
            ) AS `rank`', [$request->classId, $request->batchId, $request->exam_paper_id])
            ->first();

        

        if($Record){

            $qestionAnalysis=govtResultAttemptQuestion::where('govt_result_id' , $Record->id)
            ->select('id', 'isAnswerCorrect')
            ->get();

            return response()->json([
                'status' => true,
                'message' => 'Successfully',
                'result' => $Record,
                'qestionAnalysis'=>$qestionAnalysis,
            ], 200);

        }else{
            return response()->json([
                'status' => false,
                'message' => 'data not found!',
            ], 404);
        }

    }

    public function resultQuestionAnalysis(Request $request){
        $validator = Validator::make($request->all(),
            [
            'resultId' => 'required',
            'exam_paper_id'=>'required',

        ]);

        if ($validator->fails()) {

            return response()->json(['error'=>$validator->errors()], 401);

        }

        $paper=ExamPaper::where('id', $request->exam_paper_id)
        // ->where('status', 1)
        // ->with('attemptedResult.questionInfo')
        ->first();

   

        $question = QuestionBank::whereIn('id', json_decode($paper->question_id))
        ->with(['questionBankInfo', 'questionBankInfo.resultAttemptQuestions' => function ($query) use ($request) {
            $query->where('govt_result_id', $request->resultId)->where('exam_paper_id', $request->exam_paper_id);
        }])
        ->select('id', 'question_type', 'language_type', 'pragraph')
        ->get();

        return response()->json([
            'status' => true,
            'message' => 'Successfully',
            'attemptedResult' => $question,
        ], 200);


    }
}

<?php

namespace App\Http\Controllers\api\teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuestionBank;
use App\Models\PreviousYearQuestion;
use App\Models\QuestionBankInfo;
use Auth;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TeacherQuestionManagement extends Controller
{
    public function createNewQuestion(Request $request){

        // $validator = Validator::make($request->all(), [
        //     'subject_id' => 'required',
        //     'chapter_id' => 'required',
        //     'questionType' => 'required',
        //     'languageType' => 'required',
        //     'level' => 'required',
        //     'question' => 'required',
        //     'option1' => 'reqired',
        //     'option2' => 'required',
        //     'option3' => 'required',
        //     'option4' => 'required',
        //     'answer' => 'required',
        // ]);
    
        // if ($validator->fails()) {
        //     return response()->json(['message' => $validator->errors()], 400); // 400 Bad Request
        // }

        try{
            DB::beginTransaction();

            $level=$request->level;
            $englishQuestion=$request->languageType !='Hindi' ? '<p>'.$request->question.'</p>' : '';
            $hindiQuestion=$request->languageType !='English' ? '<p>'.$request->question.'</p>' : '';
            $englishOptionA=$request->languageType !='Hindi' ? '<p>'.$request->option1.'</p>' : '';
            $hindiOptionA=$request->languageType !='English' ? '<p>'.$request->option1.'</p>' : '';
            $englishOptionB=$request->languageType !='Hindi' ? '<p>'.$request->option2.'</p>' : '';
            $hindiOptionB=$request->languageType !='English' ? '<p>'.$request->option2.'</p>' : '';
            $englishOptionC=$request->languageType !='Hindi' ? '<p>'.$request->option3.'</p>' : '';
            $hindiOptionC=$request->languageType !='English' ? '<p>'.$request->option3.'</p>' : '';
            $englishOptionD=$request->languageType !='Hindi' ? '<p>'.$request->option4.'</p>' : '';
            $hindiOptionD=$request->languageType !='English' ? '<p>'.$request->option4.'</p>' : '';
            $englishOptionE=$request->languageType !='Hindi' ? '<p>'.$request->option5.'</p>' : '';
            $hindiOptionE=$request->languageType !='English' ? '<p>'.$request->option5.'</p>' : '';
            $answer=strtoupper($request->answer);
            $englishDes=$request->languageType !='Hindi' ? '<p>'.$request->description.'</p>' : '';
            $hindiDes=$request->languageType !='English' ? '<p>'.$request->description.'</p>' : '';
            $previous_year = $request->previous_year;


            $questionBank = new QuestionBank();
            $questionBank->subject_id =$request->subject_id;
            $questionBank->chapter_id =$request->chapter_id;
            $questionBank->question_type =$request->questionType;
            $questionBank->language_type =$request->languageType;
            $questionBank->level =$level;

            $questionBank->added_by=1;
            $questionBank->save();

            $questionInfo = new QuestionBankInfo();
            $questionInfo->question_bank_id =intVal($questionBank->id);
            $questionInfo->question =json_encode(
                ['English'=>$request->languageType !='Hindi' ? $englishQuestion : '',
                'Hindi'=>$request->languageType !='English' ? $hindiQuestion : ''
                ]);
            $questionInfo->option =json_encode(
                ['English'=>$request->languageType !='Hindi' ?
                    [
                        $englishOptionA,
                        $englishOptionB,
                        $englishOptionC,
                        $englishOptionD,
                        $englishOptionE
                    ] : '',
                'Hindi'=>$request->languageType !='English' ?
                    [
                        $hindiOptionA,
                        $hindiOptionB,
                        $hindiOptionC,
                        $hindiOptionD,
                        $hindiOptionE
                    ] : ''
                ]);
            $questionInfo->answer =$answer;
            $questionInfo->ans_desc =json_encode(
                ['English'=>$request->languageType !='Hindi' ? $englishDes : '',
                'Hindi'=>$request->languageType !='English' ? $hindiDes : ''
                ]);
            $questionInfo->added_by =1;
            $questionInfo->save();

            if ($previous_year != null) {
                $splitString = preg_split('/,\s*(?=[A-Z])/', trim($previous_year));
                foreach ($splitString as $pair) {
                    $pairArray = explode('-', $pair, 2);
                    $exam = $pairArray[0];
                    $yearArray = json_decode($pairArray[1], true);
                    foreach ($yearArray as $year) {
                        $existingData = PreviousYearQuestion::where('exam', $exam)->where('year', $year)->first();
                        if ($existingData) {
                            $prevQueIds = json_decode($existingData->questionBankInfoId);
                                array_push($prevQueIds, $questionBank->id);
                            $existingData->questionBankInfoId = json_encode($prevQueIds);
                            $existingData->save();
                        } else {
                            $questionArray = [];
                            array_push($questionArray, $questionBank->id);
                                $newQue = new PreviousYearQuestion();
                                $newQue->exam = $exam;
                                $newQue->year = $year;
                                $newQue->questionBankInfoId = json_encode($questionArray);
                                $newQue->save();
                        }
                    }
                }
            }

            
            DB::commit();
            return response()->json(["statuscode" => '201', "message" => 'Successfully', "actionType"=>"001", ]);

        
        } catch (Exception $e) {
        DB::rollback();
        return response()->json(['statuscode'=>'500', 'message'=>'Internal server error.. please contact to the Administrator!', 'errors'=>$e->getMessage()]);
        }
    }
}

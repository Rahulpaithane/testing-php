<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
use Validator;
use Carbon\Carbon;
use App\Models\quizRoom;
use App\Models\quizResult;
use App\Models\QuestionBank;
use App\Models\QuestionBankInfo;
use App\Models\StudentLedger;

class ManageLiveQuizController extends Controller
{
    public function quizRoomList(Request $request){

        $validator = Validator::make($request->all(), 
            [ 
            'prepration' => 'required', 
        ]); 

        if ($validator->fails()) {  
            return response()->json(['error'=>$validator->errors()], 401); 
        }

        $roomList=quizRoom::select('id', 'name', 'image', 'room_type', 'price', 'timeDurationPerQuestion', 'banner', 'description')->where('status','1')->get();

            return response()->json([
                'status' => true,
                'message' => 'Successfully',
                'roomList' =>$roomList,
            ], 200);
        
    } 

    public function myQuizRoom(Request $request){

        $paidRoomList=quizRoom::
            join('student_ledgers', 'quiz_rooms.id', 'student_ledgers.student_ledgerable_id')
            ->where('student_ledgers.student_id', Auth::guard('api')->user()->id)
            ->where('student_ledgers.ledger_type', 'Credit')
            ->where('student_ledgers.student_ledgerable_type', 'App\Models\quizRoom')
            ->select('quiz_rooms.id', 'quiz_rooms.name', 'quiz_rooms.image', 'quiz_rooms.room_type', 'quiz_rooms.price', 'quiz_rooms.timeDurationPerQuestion')
            ->get();


        $freeRoomList=quizRoom::select('id', 'name', 'image', 'room_type', 'price', 'timeDurationPerQuestion')
        ->where('room_type', 'Free')
        ->get();

        // $myRoomList = array_merge((array) $paidRoomList, (array) $freeRoomList);
        $myRoomList = $paidRoomList->concat($freeRoomList);

        // $myRoomList=array_merge($paidRoomList, $freeRoomList);

            return response()->json([
                'status' => true,
                'message' => 'Successfully',
                'roomList' =>$myRoomList,
            ], 200);
    }

    public function purchaseQuizRoom(Request $request){
        if($request->room_type == 'Paid'){
            $validator = Validator::make($request->all(),
                [
                'roomId' => 'required',
                'razorpay_payment_id' => 'required',
                'razorpay_order_id' => 'required',
                'razorpay_signature' => 'required',
            ]);

            if ($validator->fails()) {

                return response()->json(['error'=>$validator->errors()], 401);

            }
        } else if($request->room_type == 'Free') {
            $validator = Validator::make($request->all(),
                [
                'roomId' => 'required',
            ]);

            if ($validator->fails()) {

                return response()->json(['error'=>$validator->errors()], 401);

            }
        } else {
            return response()->json(['status'=>false, 'message'=>'Invalid Is Payable'], 401); 
        }

        $roomInfo=quizRoom::select('id', 'name', 'room_type', 'price' )
        ->where('id', $request->roomId)
        ->first();

        if($roomInfo){
            $lastLedgerRecord=StudentLedger::where('student_id', Auth::guard('api')->user()->id)->where('status', '1')->orderBy('id', 'DESC')->first();

            if(!$lastLedgerRecord){
                $lastBalance = 0;
            } else {
                $lastBalance = $lastLedgerRecord->balance;
            }
    
            $currentSaleBalance=floatval($lastBalance) + floatval($roomInfo->price ==null ? '0' : $roomInfo->price);
            $afterSoldCurrentBalance=floatVal($currentSaleBalance) - floatval($roomInfo->price ==null ? '0' : $roomInfo->price);
    
            $paymentModeDetails=[
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_signature' => $request->razorpay_signature,
            ];
    
            $debitLedger = new StudentLedger();
            $debitLedger->student_id = Auth::guard('api')->user()->id;
            $debitLedger->student_ledgerable_type = 'App\Models\quizRoom';
            $debitLedger->student_ledgerable_id = $request->roomId;
            $debitLedger->invoice_id ='';
            $debitLedger->receipt_no ='';
            $debitLedger->ledger_type ='Debit';
            $debitLedger->debit =$roomInfo->price ==null ? '0' : $roomInfo->price;
            $debitLedger->credit ='';
            $debitLedger->balance =$currentSaleBalance;
            $debitLedger->remarks ='Purchase Room ('.$roomInfo->name.')';
            $debitLedger->payment_mode ='Online';
            $debitLedger->payment_mode_details =json_encode($paymentModeDetails);
            $debitLedger->comment ='';
            $debitLedger->save();
    
            $creditLedger = new StudentLedger();
            $creditLedger->student_id = Auth::guard('api')->user()->id;
            $creditLedger->student_ledgerable_type = 'App\Models\quizRoom';
            $creditLedger->student_ledgerable_id = $request->roomId;
            $creditLedger->invoice_id ='';
            $creditLedger->receipt_no ='';
            $creditLedger->ledger_type ='Credit';
            $creditLedger->debit ='';
            $creditLedger->credit =$roomInfo->price ==null ? '0' : $roomInfo->price;
            $creditLedger->balance =$afterSoldCurrentBalance;
            $creditLedger->remarks ='Purchase Room ('.$roomInfo->name.')';;
            $creditLedger->payment_mode ='Online';
            $creditLedger->payment_mode_details =json_encode($paymentModeDetails);
            $creditLedger->comment ='';
            $creditLedger->save();
    
            return response()->json([
                'status' => true,
                'message' => 'Successfully Purchsed Book',
            ], Response::HTTP_OK);

        } else {
            return response()->json(['status'=>false, 'message'=>'Book Not Found'], 401);
        }
    }

    public function initialRoomQuestion(Request $request){
        $validator = Validator::make($request->all(), 
            [ 
            'roomId' => 'required', 
        ]); 

        if ($validator->fails()) {  
            return response()->json(['error'=>$validator->errors()], 401); 
        }

        $currentData=date("Y-m-d");

        $roomList=quizRoom::select('id', 'question_id')->where('id', $request->roomId)->first();

        $questionArr=json_decode($roomList->question_id);
        $randomKey = array_rand($questionArr);
        $randomQuestionId = $questionArr[$randomKey];

        $questionBank=QuestionBank::with('questionBankInfo')->where('id', $randomQuestionId)->first();

        $question=[
            'id' =>$questionBank->id,
            'language_type' => $questionBank->language_type,
            'question' =>$questionBank->questionBankInfo[0]->question,
            'option' =>$questionBank->questionBankInfo[0]->option,
            'answer' =>$questionBank->questionBankInfo[0]->answer,
        ];

        $userResult=quizResult::select('id', 'question_id')
        ->where('attemptDate', $currentData)
        ->where('quiz_room_id', $request->roomId)   
        ->where('student_id', Auth::guard('api')->user()->id)
        ->select('totalCorrect', 'totalWrong', 'totalSkipped', 'attemptTotalMarks')
        ->selectRaw('(
            SELECT COUNT(*) + 1 
            FROM quiz_results AS qr 
            WHERE qr.attemptTotalMarks > quiz_results.attemptTotalMarks
            AND qr.attemptDate = ?
            AND qr.quiz_room_id = ?
            
        ) AS `rank`', [$currentData, $request->roomId])
        ->first();

        if(!$userResult){
            $totalStudent=quizResult::join('students', 'quiz_results.student_id', 'students.id')
            ->where('attemptDate', $currentData)
            ->where('quiz_room_id', $request->roomId)
            ->count();
            $userResult=[
                'name' =>Auth::guard('api')->user()->name,
                'totalCorrect'=>0,
                'totalWrong'=>0,
                'totalSkipped'=>0,
                'attemptTotalMarks'=>'0',
                'rank'=>strval($totalStudent+1),
            ];
        } else {
            $userResult->name=Auth::guard('api')->user()->name;
        }

        // $leaderBoard=quizResult::
        // join('students', 'quiz_results.student_id', 'students.id')
        // ->where('attemptDate', $currentData)
        // ->where('quiz_room_id', $request->roomId)   
        // ->where('student_id', '!=', Auth::guard('api')->user()->id)
        // ->select('students.name','quiz_results.id','quiz_results.totalCorrect', 'quiz_results.totalWrong', 'quiz_results.totalSkipped', 'quiz_results.attemptTotalMarks', 'quiz_results.rank')
        // // ->orderBy('attemptTotalMarks', 'desc')
        // ->limit(5)
        // ->get();

        $leaderBoard = quizResult::join('students', 'quiz_results.student_id', 'students.id')
        ->where('attemptDate', $currentData)
        ->where('quiz_room_id', $request->roomId)   
        ->where('student_id', '!=', Auth::guard('api')->user()->id)
        ->select('students.name', 'quiz_results.id', 'quiz_results.totalCorrect', 'quiz_results.totalWrong', 'quiz_results.totalSkipped', 'quiz_results.attemptTotalMarks')
        ->selectRaw('(
            SELECT COUNT(*) + 1 
            FROM quiz_results AS qr 
            WHERE qr.attemptTotalMarks > quiz_results.attemptTotalMarks
            AND qr.attemptDate = ?
            AND qr.quiz_room_id = ?
        ) AS `rank`', [$currentData, $request->roomId])
        ->orderBy('attemptTotalMarks', 'desc')
        ->limit(50)
        ->get();

        return response()->json([
            'status' => true,
            'message' => 'Successfully',
            'userResult'=>$userResult,
            'leaderBoard'=>$leaderBoard,
            'question' =>$question,
        ], 200);

    }

    public function initialRoomQuestionforSequntial(Request $request){
        $validator = Validator::make($request->all(), 
            [ 
            'roomId' => 'required', 
        ]); 

        if ($validator->fails()) {  
            return response()->json(['error'=>$validator->errors()], 401); 
        }

        $currentData=date("Y-m-d");

        $roomList=quizRoom::select('id', 'question_id')->where('id', $request->roomId)->first();

        $questionArr=json_decode($roomList->question_id);

        if($request->isQuestionSequntial){
            if(!isset($request->currentQuestionPosition)){
                $randomKey = 0;
                
            } else {
                $lengthQuestionArr=count($questionArr);
                if(intval($request->currentQuestionPosition) < $lengthQuestionArr - 1){
                    $randomKey = intval($request->currentQuestionPosition) + 1 ;
                } else {
                    $randomKey = 0;
                } 
                
            }
            
        } else {
            $randomKey = array_rand($questionArr);
        }
        
        $randomQuestionId = $questionArr[$randomKey];

        $questionBank=QuestionBank::with('questionBankInfo')->where('id', $randomQuestionId)->first();

        $question=[
            'id' =>$questionBank->id,
            'language_type' => $questionBank->language_type,
            'question' =>$questionBank->questionBankInfo[0]->question,
            'option' =>$questionBank->questionBankInfo[0]->option,
            'answer' =>$questionBank->questionBankInfo[0]->answer,
        ];

        $userResult=quizResult::select('id', 'question_id')
        ->where('attemptDate', $currentData)
        ->where('quiz_room_id', $request->roomId)   
        ->where('student_id', Auth::guard('api')->user()->id)
        ->select('totalCorrect', 'totalWrong', 'totalSkipped', 'attemptTotalMarks')
        ->selectRaw('(
            SELECT COUNT(*) + 1 
            FROM quiz_results AS qr 
            WHERE qr.attemptTotalMarks > quiz_results.attemptTotalMarks
            AND qr.attemptDate = ?
            AND qr.quiz_room_id = ?
            
        ) AS `rank`', [$currentData, $request->roomId])
        ->first();

        if(!$userResult){
            $totalStudent=quizResult::join('students', 'quiz_results.student_id', 'students.id')
            ->where('attemptDate', $currentData)
            ->where('quiz_room_id', $request->roomId)
            ->count();
            $userResult=[
                'name' =>Auth::guard('api')->user()->name,
                'totalCorrect'=>0,
                'totalWrong'=>0,
                'totalSkipped'=>0,
                'attemptTotalMarks'=>'0',
                'rank'=>strval($totalStudent+1),
            ];
        } else {
            $userResult->name=Auth::guard('api')->user()->name;
        }

        // $leaderBoard=quizResult::
        // join('students', 'quiz_results.student_id', 'students.id')
        // ->where('attemptDate', $currentData)
        // ->where('quiz_room_id', $request->roomId)   
        // ->where('student_id', '!=', Auth::guard('api')->user()->id)
        // ->select('students.name','quiz_results.id','quiz_results.totalCorrect', 'quiz_results.totalWrong', 'quiz_results.totalSkipped', 'quiz_results.attemptTotalMarks', 'quiz_results.rank')
        // // ->orderBy('attemptTotalMarks', 'desc')
        // ->limit(5)
        // ->get();

        $leaderBoard = quizResult::join('students', 'quiz_results.student_id', 'students.id')
        ->where('attemptDate', $currentData)
        ->where('quiz_room_id', $request->roomId)   
        ->where('student_id', '!=', Auth::guard('api')->user()->id)
        ->select('students.name', 'quiz_results.id', 'quiz_results.totalCorrect', 'quiz_results.totalWrong', 'quiz_results.totalSkipped', 'quiz_results.attemptTotalMarks')
        ->selectRaw('(
            SELECT COUNT(*) + 1 
            FROM quiz_results AS qr 
            WHERE qr.attemptTotalMarks > quiz_results.attemptTotalMarks
            AND qr.attemptDate = ?
            AND qr.quiz_room_id = ?
        ) AS `rank`', [$currentData, $request->roomId])
        ->orderBy('attemptTotalMarks', 'desc')
        // ->limit(5)
        ->get();

        return response()->json([
            'status' => true,
            'message' => 'Successfully',
            'userResult'=>$userResult,
            'leaderBoard'=>$leaderBoard,
            'question' =>$question,
            'currentQuestionPosition'=>$randomKey,
        ], 200);

    }

    public function submitRoomQuestion(Request $request){
        $validator = Validator::make($request->all(), 
            [ 
            'roomId' => 'required', 
            'attemptStatus' => 'required',
        ]); 

        if ($validator->fails()) {  
            return response()->json(['error'=>$validator->errors()], 401); 
        }

        $currentData=date("Y-m-d");

        $userResult=quizResult::
        where('attemptDate', $currentData)
        ->where('quiz_room_id', $request->roomId)   
        ->where('student_id', Auth::guard('api')->user()->id)
        ->select('id','totalCorrect', 'totalWrong', 'totalSkipped', 'attemptTotalMarks', 'rank')
        ->first();
        

        $negativeMarks='0.5';
        
        if($userResult){
            // return $userResult->totalWrong;
            // $result2 = QuizResult::find($userResult->id);
            // return $userResult->totalCorrect;
            if($request->attemptStatus == 'Right'){
            
                $userResult->totalCorrect=$userResult->totalCorrect + 1;
                $userResult->attemptTotalMarks=$userResult->attemptTotalMarks == '' ? '1' : floatval($userResult->attemptTotalMarks) + 1;
            
            }

            if($request->attemptStatus == 'Wrong'){
                $userResult->totalWrong=$userResult->totalWrong == '' ? '1' : intVal($userResult->totalWrong) + 1;
                $userResult->attemptTotalMarks=$userResult->attemptTotalMarks == '' ? $negativeMarks : floatval($userResult->attemptTotalMarks) - floatval($negativeMarks);
            }

            if($request->attemptStatus == 'Skipped'){
                $userResult->totalSkipped=$userResult->totalSkipped == '' ? '1' : intVal($userResult->totalSkipped) + 1;
            }

            $userResult->save();
            
        } else {

            if($request->attemptStatus == 'Right'){
                $attemptTotalMarks='1';
            } else if($request->attemptStatus == 'Wrong'){
                $attemptTotalMarks=$negativeMarks;
            } else {
                $attemptTotalMarks='0';
            }
            

            $result = new quizResult();
            $result->quiz_room_id = $request->roomId;
            $result->student_id = Auth::guard('api')->user()->id;
            $result->attemptDate = $currentData;
            $result->totalCorrect = $request->attemptStatus == 'Right' ? 1 : 0;
            $result->totalWrong = $request->attemptStatus == 'Wrong' ? 1 : 0;
            $result->totalSkipped = $request->attemptStatus == 'Skipped' ? 1 : 0;
            $result->attemptTotalMarks = $attemptTotalMarks;
            $result->rank = '0';
            $result->save();

          
        }

        return $this->initialRoomQuestion($request);
        // return $this->initialRoomQuestionforSequntial($request);

    }
}

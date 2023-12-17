<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
use Validator;
use App\Models\Student;
use App\Models\ClassModel;
use App\Models\Batch;
use App\Models\ExamPaper;
use App\Models\StudentLedger;
use Razorpay\Api\Api;

class ExamController extends Controller
{
    public function allExam(Request $request){
        // $batchModel = new batch();
        // $batchModel->purchase(1);
        // $batchModel->studentLedger(Auth::guard('api')->user()->id);

        $auth=Auth::guard('api')->user();
        // $data=ClassModel::with('batch','batch.purchase')
        // ->select('id', 'name', 'image')
        // ->where('status', '1')
        // ->where('prepration', $request->preprationType)
        // ->get();

        // $data = ClassModel::with(['batch.purchase' => function ($query)  {
        //     $query->purchase(1);
        // }])
        //     ->select('id', 'name', 'image')
        //     ->where('status', '1')
        //     // ->where('preparation', $request->preparationType)
        //     ->get();

        $data = ClassModel::with('batch')
        ->select('id', 'name', 'image')
        ->where('status', '1')
        ->where('prepration', $request->preprationType)
        ->get();

        return response()->json([
            'status' => true,
            'message' => 'Successfully',
            'data' =>$data,
        ], 200);
    }

    public function allBatch(Request $request){
        $validator = Validator::make($request->all(),
                [
                'classId' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }

        $data = batch::select('id', 'class_id', 'name', 'badge', 'batch_image')
        ->where('status', '1')
        ->where('class_id', $request->classId)
        ->get();

        return response()->json([
            'status' => true,
            'message' => 'Successfully',
            'batch' =>$data,
        ], 200);
    }

    public function initiatePayment(Request $request)
    {
        $api = new Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));

        // Create a new Razorpay order
        $order = $api->order->create([
            'amount' => $request->amount * 100, // Convert amount to paise (Indian currency)
            'currency' => 'INR',
            'receipt' => 'order_' . uniqid(),
            'payment_capture' => 1,
        ]);

        return response()->json([
            'order_id' => $order->id,
            'razorpayId' =>env('RAZORPAY_KEY_ID'),
            'currency' => $order->currency,
            'amount' => $order->amount,
            'userId' => Auth::guard('api')->user()->id,
            'userName' => Auth::guard('api')->user()->name,
            'userEmail' => Auth::guard('api')->user()->email,
            'userMobile' => Auth::guard('api')->user()->mobile,
        ], Response::HTTP_OK);
    }

    public function purchaseBatch(Request $request){

        if($request->batchType == 'Paid'){
            $validator = Validator::make($request->all(),
                [
                'batchId' => 'required',
                'razorpay_payment_id' => 'required',
                'razorpay_order_id' => 'required',
                'razorpay_signature' => 'required',
            ]);

            if ($validator->fails()) {

                return response()->json(['error'=>$validator->errors()], 401);

            }
        } else if($request->batchType == 'Free') {
            $validator = Validator::make($request->all(),
                [
                'batchId' => 'required',
            ]);

            if ($validator->fails()) {

                return response()->json(['error'=>$validator->errors()], 401);

            }
        }

        if(StudentLedger::where('student_id', Auth::guard('api')->user()->id)
        ->where('status', '1')->where('student_ledgerable_type', 'App\Models\Batch')
        ->where('student_ledgerable_id', $request->batchId)
        ->first()){
            return response()->json(['status'=>false, 'message'=>'Batch Already Purchased'], 401);
        }

        $batch=Batch::where('id', $request->batchId)->where('status', '1')->first();

        if($batch){
            $lastLedgerRecord=StudentLedger::where('student_id', Auth::guard('api')->user()->id)->where('status', '1')->orderBy('id', 'DESC')->first();
            if(!$lastLedgerRecord){
                $lastBalance = 0;
            } else {
                $lastBalance = $lastLedgerRecord->balance;
            }

            $currentSaleBalance=floatval($lastBalance) + floatval($batch->batch_offer_price);
            $afterSoldCurrentBalance=floatVal($currentSaleBalance) - floatval($batch->batch_offer_price);

            $paymentModeDetails=[
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_signature' => $request->razorpay_signature,
            ];

            $debitLedger = new StudentLedger();
            $debitLedger->student_id = Auth::guard('api')->user()->id;
            $debitLedger->student_ledgerable_type = 'App\Models\Batch';
            $debitLedger->student_ledgerable_id = $request->batchId;
            $debitLedger->invoice_id ='';
            $debitLedger->receipt_no ='';
            $debitLedger->ledger_type ='Debit';
            $debitLedger->debit =$batch->batch_offer_price;
            $debitLedger->credit ='';
            $debitLedger->balance =$currentSaleBalance;
            $debitLedger->remarks ='Purchase Batch ('.$batch->name.')';
            $debitLedger->payment_mode ='Online';
            $debitLedger->payment_mode_details =json_encode($paymentModeDetails);
            $debitLedger->comment ='';
            $debitLedger->save();

            $creditLedger = new StudentLedger();
            $creditLedger->student_id = Auth::guard('api')->user()->id;
            $creditLedger->student_ledgerable_type = 'App\Models\Batch';
            $creditLedger->student_ledgerable_id = $request->batchId;
            $creditLedger->invoice_id ='';
            $creditLedger->receipt_no ='';
            $creditLedger->ledger_type ='Credit';
            $creditLedger->debit ='';
            $creditLedger->credit =$batch->batch_offer_price;
            $creditLedger->balance =$afterSoldCurrentBalance;
            $creditLedger->remarks ='Purchase Batch ('.$batch->name.')';;
            $creditLedger->payment_mode ='Online';
            $creditLedger->payment_mode_details =json_encode($paymentModeDetails);
            $creditLedger->comment ='';
            $creditLedger->save();



            return response()->json([
                'status' => true,
                'message' => 'Successfully Purchsed Batch',
            ], Response::HTTP_OK);

        } else {
            return response()->json(['status'=>false, 'message'=>'Batch Not Found'], 401);
        }

    }

    public function myExam(Request $request){

        // $lastLedgerRecord=StudentLedger::where('student_id', Auth::guard('api')->user()->id)
        // ->select('studentLedgerable_id')
        // ->where('studentLedgerable_type', 'Batch')
        // ->where('ledger_type', 'Debit')
        // ->get();

        // $batch=Batch::whereIn('id',  json_decode($lastLedgerRecord->studentLedgerable_id) )
        // ->where('status', '1')
        // ->get();

        $lastLedgerRecord = StudentLedger::where('student_id', Auth::guard('api')->user()->id)
            ->select('student_ledgerable_id')
            ->where('student_ledgerable_type', 'App\Models\Batch')
            ->where('ledger_type', 'Debit')
            ->get();

        $batch = Batch::whereIn('id', $lastLedgerRecord->pluck('student_ledgerable_id'))
            ->where('status', '1')
            ->select('id', 'class_id', 'name', 'badge', 'start_date', 'end_date', 'batch_type', 'batch_price', 'batch_offer_price', 'batch_image')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Purchsed Batch',
            'activeBatchId' => Auth::guard('api')->user()->activeBatchId,
            'batch' => $batch,
        ], Response::HTTP_OK);


    }
}

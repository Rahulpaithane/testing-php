<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
use Validator;
use Carbon\Carbon;
use App\Models\ManageBook;
use App\Models\StudentLedger;

class ManageBookController extends Controller
{
    public function bookList(Request $request){
        $validator = Validator::make($request->all(), 
            [ 
            'book_type' => 'required', 
        ]); 

        if ($validator->fails()) {  
            return response()->json(['error'=>$validator->errors()], 401); 
        }

        if($request->book_type=='Physical' || $request->book_type=='E-Book'){

            $bookList=ManageBook::where('book_type', $request->book_type)
            ->select('id', 'book_name', 'author', 'publication', 'class as classes', 'is_payable', 'price', 'thumbnail', 'description')
            ->get();
       
            return response()->json([
                'status' => true,
                'message' => 'Successfully',
                'bookList' =>$bookList,
            ], 200);

        } else {
            return response()->json(['status'=>false, 'message'=>'Invalid Book Type'], 401); 
        }
    }

    public function purchaseBook(Request $request){
        if($request->is_payable == '1'){
            $validator = Validator::make($request->all(),
                [
                'bookId' => 'required',
                'razorpay_payment_id' => 'required',
                'razorpay_order_id' => 'required',
                'razorpay_signature' => 'required',
            ]);

            if ($validator->fails()) {

                return response()->json(['error'=>$validator->errors()], 401);

            }
        } else if($request->is_payable == '0') {
            $validator = Validator::make($request->all(),
                [
                'bookId' => 'required',
            ]);

            if ($validator->fails()) {

                return response()->json(['error'=>$validator->errors()], 401);

            }
        } else {
            return response()->json(['status'=>false, 'message'=>'Invalid Is Payable'], 401); 
        }

        $bookInfo=ManageBook::select('id', 'book_name', 'author', 'publication', 'class', 'is_payable', 'price', 'thumbnail', 'description')
        ->where('id', $request->bookId)
        ->first();

        if($bookInfo){
            $lastLedgerRecord=StudentLedger::where('student_id', Auth::guard('api')->user()->id)->where('status', '1')->orderBy('id', 'DESC')->first();

            if(!$lastLedgerRecord){
                $lastBalance = 0;
            } else {
                $lastBalance = $lastLedgerRecord->balance;
            }
    
            $currentSaleBalance=floatval($lastBalance) + floatval($bookInfo->price ==null ? '0' : $bookInfo->price);
            $afterSoldCurrentBalance=floatVal($currentSaleBalance) - floatval($bookInfo->price ==null ? '0' : $bookInfo->price);
    
            $paymentModeDetails=[
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_signature' => $request->razorpay_signature,
            ];
    
            $debitLedger = new StudentLedger();
            $debitLedger->student_id = Auth::guard('api')->user()->id;
            $debitLedger->student_ledgerable_type = 'App\Models\ManageBook';
            $debitLedger->student_ledgerable_id = $request->bookId;
            $debitLedger->invoice_id ='';
            $debitLedger->receipt_no ='';
            $debitLedger->ledger_type ='Debit';
            $debitLedger->debit =$bookInfo->price ==null ? '0' : $bookInfo->price;
            $debitLedger->credit ='';
            $debitLedger->balance =$currentSaleBalance;
            $debitLedger->remarks ='Purchase Book ('.$bookInfo->book_name.')';
            $debitLedger->payment_mode ='Online';
            $debitLedger->payment_mode_details =json_encode($paymentModeDetails);
            $debitLedger->comment ='';
            $debitLedger->save();
    
            $creditLedger = new StudentLedger();
            $creditLedger->student_id = Auth::guard('api')->user()->id;
            $creditLedger->student_ledgerable_type = 'App\Models\ManageBook';
            $creditLedger->student_ledgerable_id = $request->bookId;
            $creditLedger->invoice_id ='';
            $creditLedger->receipt_no ='';
            $creditLedger->ledger_type ='Credit';
            $creditLedger->debit ='';
            $creditLedger->credit =$bookInfo->price ==null ? '0' : $bookInfo->price;
            $creditLedger->balance =$afterSoldCurrentBalance;
            $creditLedger->remarks ='Purchase Book ('.$bookInfo->book_name.')';;
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

    public function myEBook(Request $request){

        $bookList=ManageBook::
            join('student_ledgers', 'manage_books.id', 'student_ledgers.student_ledgerable_id')
            ->where('manage_books.book_type', 'E-Book')
            ->where('student_ledgers.student_ledgerable_type', 'App\Models\ManageBook')
            ->select('manage_books.id', 'manage_books.book_name', 'manage_books.author', 'manage_books.publication', 'manage_books.class', 'manage_books.is_payable', 'manage_books.price', 'manage_books.thumbnail', 'manage_books.description')
            ->get();

            return response()->json([
                'status' => true,
                'message' => 'Successfully',
                'bookList' =>$bookList,
            ], 200);

    }

    public function viewEBook(Request $request){
        $validator = Validator::make($request->all(),
            [
            'bookId' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json(['error'=>$validator->errors()], 401);

        }

        $bookInfo=ManageBook::select('id', 'book_name', 'attachment', 'description')
        ->where('id', $request->bookId)
        ->first();

        return response()->json([
            'status' => true,
            'message' => 'Successfully',
            'bookInfo' =>$bookInfo,
        ], 200);
    }
}

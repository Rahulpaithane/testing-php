<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\Student;
use App\Models\ClassModel;
use App\Models\Batch;
use App\Models\ExamPaper;
use App\Models\MobileSlider;
use App\Models\StudentLedger;
use Razorpay\Api\Api;

class StudentProfile extends Controller
{
    public function preference(Request $request){

        $validator = Validator::make($request->all(), 
            [ 
            'preprationType' => 'required',
        ]); 

        if ($validator->fails()) {  
            return response()->json(['error'=>$validator->errors()], 401); 
        }

        $user = Student::where('id', Auth::guard('api')->user()->id)->first();

        if (!$user){
            return response()->json([
                'status' => false,
                'message' => 'Something Problem!',
            ], 401);
        }
        $user->prepration=$request->preprationType;

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Successfully Updated Perference',
        ], 200);

    }

    public function activateBatch(Request $request){

        $validator = Validator::make($request->all(), 
            [ 
            'batchId' => 'required',
        ]); 

        if ($validator->fails()) {  
            return response()->json(['error'=>$validator->errors()], 401); 
        }

        $user = Student::where('id', Auth::guard('api')->user()->id)->first();

        if (!$user){
            return response()->json([
                'status' => false,
                'message' => 'Something Problem!',
            ], 401);
        }

        $user->activeBatchId=$request->batchId;

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Successfully Updated Batch',
        ], 200);

    }

    public function activateClass(Request $request){

        $validator = Validator::make($request->all(), 
            [ 
            'classId' => 'required',
        ]); 

        if ($validator->fails()) {  
            return response()->json(['error'=>$validator->errors()], 401); 
        }

        $user = Student::where('id', Auth::guard('api')->user()->id)->first();

        if (!$user){
            return response()->json([
                'status' => false,
                'message' => 'Something Problem!',
            ], 401);
        }

        $user->activeClassId=$request->classId;

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Successfully Updated Exam Category',
        ], 200);

    }

    public function viewProfile(Request $request){
        return response()->json([
            'status' => true,
            'message' => 'Successfully',
            'data' =>[
                'userId' => Auth::guard('api')->user()->id,
                'name' => Auth::guard('api')->user()->name,
                'email' => Auth::guard('api')->user()->email,
                'mobile' => Auth::guard('api')->user()->mobile,
                'profile_image' => Auth::guard('api')->user()->profile_image,
                'gender' => Auth::guard('api')->user()->gender,
                'dob' => Auth::guard('api')->user()->dob,
                'aadhar_no' => Auth::guard('api')->user()->aadhar_no,
                'address' => Auth::guard('api')->user()->address,
                'prepration' => Auth::guard('api')->user()->prepration,

            ],
        ], 200);
    }

    public function updateProfile(Request $request){
        $user = Student::where('id', Auth::guard('api')->user()->id)->first();

        if (!$user){
            return response()->json([
                'status' => false,
                'message' => 'Something Problem!',
            ], 401);
        }

        $request->name !='' ? $user->name=$request->name : '';
        $request->email !='' ? $user->email=$request->email : '';
        $request->gender !='' ? $user->gender=$request->gender : '';
        $request->dob !='' ? $user->dob=$request->dob : '';
        $request->aadhar_no !='' ? $user->aadhar_no=$request->aadhar_no : '';
        $request->address !='' ? $user->address=$request->address : '';
        $request->preprationType !='' ? $user->prepration=$request->preprationType : '';

        if (isset($request->profile_image) != null) {
            $filename = uniqid() . $request->profile_image->getClientOriginalName();
            $path = 'images/'.$filename;
            $request->profile_image->move(public_path('images'), $filename);

            $user->profile_image=$path;
        }else{
            $path = '';
        }

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Successfully',
        ], 200);
    }

    public function homeProfile(Request $request){
        if(Auth::guard('api')->user()->activeBatchId !=''){
            $batchInfo=Batch::where('id', Auth::guard('api')->user()->activeBatchId)
            ->select('id', 'name')->first();
        } else {
            $batchInfo=false;
        }

        if(Auth::guard('api')->user()->activeClassId !=''){
            $classInfo=ClassModel::where('id', Auth::guard('api')->user()->activeClassId)
            ->select('id', 'name')->first();
        } else {
            $classInfo=false;
        }


        $mobileSlider = MobileSlider::leftJoin('batches', 'mobile_sliders.batch_id', '=', 'batches.id')
            ->where('mobile_sliders.prepration', Auth::guard('api')->user()->prepration) // Corrected column name
            ->where('mobile_sliders.status', 1)
            ->select('mobile_sliders.id', 'mobile_sliders.prepration', 'mobile_sliders.image', 'mobile_sliders.isClickable', 'mobile_sliders.batch_id', 'batches.name as batchName')
            ->get();

        $latestBatch = Batch::select('id', 'class_id', 'name', 'badge', 'batch_image')
            ->where('status', '1')
            ->where('class_id', Auth::guard('api')->user()->activeClassId)
            ->latest()
            ->limit(10) // Limit the result to 10 records
            ->get();

        $popularBatch = Batch::select('id', 'class_id', 'name', 'badge', 'batch_image')
            ->where('status', '1')
            ->where('class_id', Auth::guard('api')->user()->activeClassId)
            ->where('is_popular', '1')
            ->latest()
            // ->limit(10) // Limit the result to 10 records
            ->get();
        
        return response()->json([
            'status' => true,
            'message' => 'Successfully',
            'data' =>[
                'userId' => Auth::guard('api')->user()->id,
                'name' => Auth::guard('api')->user()->name,
                'profile_image' => Auth::guard('api')->user()->profile_image,
                'prepration' => Auth::guard('api')->user()->prepration,
                'batchId' => Auth::guard('api')->user()->activeBatchId,
                'batchName' => $batchInfo ==true ? $batchInfo->name : '',
                'activeClassId' => Auth::guard('api')->user()->activeClassId,
                'className' => $classInfo ==true ? $classInfo->name : '',
            ],
            'slider' =>$mobileSlider,
            'latestBatch' =>$latestBatch,
            'popularBatch' =>$popularBatch,

        ], 200);
    }

    public function transactionList(Request $request){

        $transactionList = StudentLedger::where('student_id', Auth::guard('api')->user()->id)
        ->where('ledger_type', 'Credit')
        ->select('id', 'remarks', 'payment_mode', 'credit as amount', 'payment_mode_details as payment_id', 'created_at as date')
        ->orderBy('id', 'DESC')
        ->get();

        return response()->json([
            'status' => true,
            'message' => 'Successfully',
            'transactionList' =>$transactionList
        ], 200);
    }

    

    

    // public function initialPayment(Request $request){
    //     $receiptId = Str::random(20);

    //         $api = new Api(config('app.razor_key'), config('app.razor_secret'));

    //         // ($request->all()['amount'] < 100)? 100 : $request->all()['amount']

    //             $order = $api->order->create(array(
    //                 'receipt' => $receiptId,
    //                 'amount' => $request->all()['amount'],
    //                 'currency' => 'INR'
    //                 )
    //             );
    //             // Let's create the razorpay payment page
    //             $response = [
    //                 'orderId' => $order['id'],
    //                 'razorpayId' => config('app.razor_key'),
    //                 'amount' => $request->all()['amount'],
    //                 'name' => $request->all()['name'],
    //                 'currency' => 'INR',
    //                 'mobile' => $request->all()['mobile'],
    //                 'address' => $request->all()['address'],
    //                 // 'description' => 'Testing description',
    //             ];
    // }
}

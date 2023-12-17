<?php

namespace App\Http\Controllers\api\teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InstituteTeacher;
use Auth;
use Carbon\Carbon;
use Validator;
use Illuminate\Support\Facades\Hash;

class TeacherProfileController extends Controller
{
    public function viewProfile(Request $request){
        return response()->json([
            'status' => true,
            'message' => 'Successfully',
            'data' =>[
                'userId' => Auth::guard('institute')->user()->id,
                'instituteName' => Auth::guard('institute')->user()->instituteName,
                'teacherName' => Auth::guard('institute')->user()->teacherName,
                'email' => Auth::guard('institute')->user()->email,
                'mobile' => Auth::guard('institute')->user()->mobile,
                'institute_image' => Auth::guard('institute')->user()->institute_image,
                'profile_image' => Auth::guard('institute')->user()->profile_image,
                'gender' => Auth::guard('institute')->user()->gender,
                'address' => Auth::guard('institute')->user()->address,
            ],
        ], 200);
    }

    public function updateProfile(Request $request){
        $user = InstituteTeacher::where('id', Auth::guard('institute')->user()->id)->first();

        if (!$user){
            return response()->json([
                'status' => false,
                'message' => 'Something Problem!',
            ], 401);
        }

        $request->instituteName !='' ? $user->instituteName=$request->instituteName : '';
        $request->teacherName !='' ? $user->teacherName=$request->teacherName : '';
        $request->email !='' ? $user->email=$request->email : '';
        $request->gender !='' ? $user->gender=$request->gender : '';
        $request->address !='' ? $user->address=$request->address : '';


        if (isset($request->profile_image) != null) {
            $filename = uniqid() . $request->profile_image->getClientOriginalName();
            $path = 'images/'.$filename;
            $request->profile_image->move(public_path('images'), $filename);

            $user->profile_image=$path;
        }else{
            $path = '';
        }

        if (isset($request->institute_image) != null) {
            $filename = uniqid() . $request->institute_image->getClientOriginalName();
            $path = 'images/'.$filename;
            $request->institute_image->move(public_path('images'), $filename);

            $user->institute_image=$path;
        }else{
            $path = '';
        }

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Successfully',
        ], 200);
    }

    public function resetPassword(Request $request){
     
        $validator = Validator::make($request->all(), 
        [ 

        'currentPassword' => 'required',  
        'newPassword' => 'required', 
        'confirmPassword' => 'required', 
    ]); 

    if ($validator->fails()) {  
        return response()->json(['message'=>$validator->errors()], 401); 
    }

        $pwd = InstituteTeacher::select('id', 'password', 'updated_at')->where('id', Auth::guard('institute')->user()->id)->first();
        $checkOldPwd = Hash::check($request->currentPassword, $pwd->password);
        if(!$checkOldPwd){
        return response()->json(['message'=>'Old Password does not matched!'], 401);
        }

        if($request->newPassword != $request->confirmPassword){
            return response()->json(['message'=>'New Password OR Confirm Password does not matched!'], 401);
        }
        // DB::beginTransaction();
        InstituteTeacher::where('id', Auth::guard('institute')->user()->id)
        ->update([
            'password'=>Hash::make($request->newPassword),
            'updated_at'=> Carbon::now('Asia/Kolkata')
        ]);
    
        return response()->json(['message'=>'Password has been updated Successfully!'], 200);

}
}

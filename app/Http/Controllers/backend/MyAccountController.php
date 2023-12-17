<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
// use App\Mail\AdminPasswordReset;
use Carbon\Carbon;
use Exception;

class MyAccountController extends Controller
{
    //TO GOTO PROFILE VIEW
    public function profile(){
        try {
            $data = User::find(Auth::guard('admin')->user()->id);
            // dd($data);
            return view('backend.profile.profile', ['data' => $data]);
        } catch (Exception $e) {
            return redirect()->route('exceptionPage')->with(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function updateProfile(Request $request){

        if (isset($request->profile_image) != null) {
            $filename = uniqid() . $request->profile_image->getClientOriginalName();
            $path = 'images/'.$filename;
            $request->profile_image->move(public_path('images'), $filename);
        }else{
                $path = $request->existing_teacher_image;
        }

       $user= User::where('id', '=', $request->id)->first();
        $user->name=$request->name;
        $user->mobile=$request->mobile;
        $user->aadhar_no=$request->aadhar_no;
        $user->gender=$request->gender;
        $user->education=$request->education;
        $user->address=$request->address;
        $user->profile_image=$path;
        $user->save();

        return response()->json([ 'statuscode' => '201', 'actionType'=>'001', 'message'=>'Profile has been updated Successfully!']);

    }
    //TO SIGNOUT THE SESSION
    public function signOut(Request $request){
        Auth::guard('admin')->logout();
        
        if ($request->domainRoute == false) {
            return redirect(tenant_route(request()->getHost(),'tenantAdmin.login'));
        } else{
            return redirect()->route('admin.login2');
        }
        
        
    }

    //TO UPDATE THE PASSWORD BY USING PREVIOUS PASSWORD
    public function updatePassword(Request $request){
        // try{
            $pwd = User::select('id', 'password', 'updated_at')->where('id', $request->id)->where('email', '=', $request->email)->first();
            $checkOldPwd = Hash::check($request->old_password, $pwd->password);
            if(!$checkOldPwd){
            return response()->json([ 'statuscode' => '401','message'=>'Old Password does not matched!']);
            }

            if($request->new_password != $request->confirm_password){
                return response()->json([ 'statuscode' => '401','message'=>'New Password OR Confirm Password does not matched!']);
            }
            // DB::beginTransaction();
            User::where('email', $request->email)
            ->where('id', $request->id)
            ->update([
                'password'=>Hash::make($request->confirm_password),
                'updated_at'=> Carbon::now('Asia/Kolkata')
            ]);
            // DB::commit();
            return response()->json([ 'statuscode' => '201', 'actionType'=>'001', 'message'=>'Password has been updated Successfully!']);
        // }catch(Exception $e){
        //     DB::rollback();
        //     return response()->json(['statuscode'=>'500', 'message'=>'Internal server error.. please contact to the Administrator!', 'errors'=>$e->getMessage()]);
        // }
    }
}

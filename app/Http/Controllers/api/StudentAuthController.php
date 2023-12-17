<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Auth;
use Validator;
use App\Models\Student;
use App\Helpers\SendOTPHelper;
use App\Mail\SendOTPEmail;
use App\Jobs\SendOTPEmailJob;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class StudentAuthController extends Controller
{
    public $token = true;

    public function signup(Request $request){

        $validator = Validator::make($request->all(), 
            [ 
            'name' => 'required',
            'mobile' => 'required|digits:10',
            'email' => 'required|email',
            'password' => 'required',  
            'c_password' => 'required|same:password', 
        ]); 

        if ($validator->fails()) {  
 
            return response()->json(['error'=>$validator->errors()], 401); 

        }
        
        $user = new Student();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->password =Hash::make($request->password);

        if(Student::where('mobile', $user->mobile)->first()){
            return response()->json(['status'=>false, 'message'=>'Mobile Number Already Registerd'], 401); 
        }
        if(Student::where('email', $user->email)->first()){
            return response()->json(['status'=>false, 'message'=>'Email Id Already Registerd'], 401); 
        }
        $user->save();


        if ($this->token) {
            return $this->loginWithPassword($request);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully Login',
            'data' => $user
        ], Response::HTTP_OK);

    }

    public function registeredEmailMobileSendOtp(Request $request){
        try {
            // $validator = Validator::make($request->all(), 
            // [
            //     'mobile' => 'required|digits:10',
            // ]);

            $validator = Validator::make($request->all(), [
                'id' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        // Validate as a mobile number
                        if (!preg_match('/^\d{10}$/', $value)) {
                            // Validation failed as a mobile number
                            // Check if it's a valid email address instead
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                // Validation failed as a mobile number and email address
                                $fail('Invalid mobile number or email address');
                            }
                        }
                    },
                ],
            ]);

            if ($validator->fails()) {  
 
                return response()->json(['error'=>$validator->errors()], 401); 
    
            }

            $type=!preg_match('/^\d{10}$/', $request->id) ? 'emailType' : 'mobileType';

            $user = Student::where('mobile', $request->id)
            ->orWhere('email', $request->id)
            ->first();

            // $user = Student::where('mobile', $request->mobile)->first();
            $otp = rand(111111,999999);

            if (!$user){
                return response()->json([
                    'status' => false,
                    'message' => 'Mobile Number Not Found!',
                ], 401);
            }

            if($type =='mobileType'){
                $sms_text = "Dear User, One Time Password(OTP) to login your password at DishTestBoard is : ".$otp." Do not disclose OTP to anyone.";
                $sendArr = array('mobile'=>$user->mobile, 'sms_text'=>$sms_text);
                SendOTPHelper::otpGenerator($sms_text, $user->mobile);
                $user->reset_token = Hash::make($otp) ;
            }

            if($type =='emailType'){
                $data = array('email'=>$user->email, 'otp'=>$otp, 'name'=>$user->name, 'msg_text'=>'Dear User This is Your One Time Password(OTP) to forget your password. Do not disclose OTP to anyone.', 'subject'=>'DishaTestBoard | Forget Password OTP Request');
                // Mail::to($user->email)->send(new SendOTPEmail($resetToken));
                SendOTPEmailJob::dispatch($data)->delay(Carbon::now()->addSeconds(6));
                $user->reset_token = Hash::make($otp);
            }

            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'A One Time Password(OTP) has been sent to your registered '.($type=="mobileType" ? "Mobile Number" : "Email Id"). '',
                'user' =>['id'=>$user->id, 'otp'=>$otp],
            ], 200);

        } catch (Exception $e) {
            return response()->json(['status'=>'error','message'=>$e->getMessage()], 400);
        }

    }

    public function withoutRegisteredMobileSendOtp(Request $request){
        try {
            $validator = Validator::make($request->all(), 
            [
                'mobile' => 'required|digits:10',
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {  
 
                return response()->json(['error'=>$validator->errors()], 401); 
    
            }

            $userMobile = Student::where('mobile', $request->mobile)
            ->first();

            $userEmail = Student::where('email', $request->email)
            ->first();

            if ($userMobile){
                return response()->json([
                    'status' => false,
                    'message' => 'Mobile Number Already Registered!',
                ], 401);
            }

            if ($userEmail){
                return response()->json([
                    'status' => false,
                    'message' => 'Email Id Already Registered!',
                ], 401);
            }

            $otp = rand(111111,999999);

            $type='mobileType';

            if($type =='mobileType'){
                $sms_text = "Dear User, One Time Password(OTP) to login your password at DishTestBoard is : ".$otp." Do not disclose OTP to anyone.";
                $sendArr = array('mobile'=>$request->mobile, 'sms_text'=>$sms_text);
                SendOTPHelper::otpGenerator($sms_text, $request->mobile);
            }

            if($type =='emailType'){
                $data = array('email'=>$request->email, 'otp'=>$otp, 'name'=>$request->name, 'msg_text'=>'Dear User This is Your One Time Password(OTP) to forget your password. Do not disclose OTP to anyone.', 'subject'=>'DishaTestBoard | Forget Password OTP Request');
                // Mail::to($user->email)->send(new SendOTPEmail($resetToken));
                SendOTPEmailJob::dispatch($data)->delay(Carbon::now()->addSeconds(6));
            }


            return response()->json([
                'status' => true,
                'message' => 'A One Time Password(OTP) has been sent to your registered '.($type=="mobileType" ? "Mobile Number" : "Email Id"). '',
                'user' =>['otp'=>$otp],
            ], 200);

        } catch (Exception $e) {
            return response()->json(['status'=>'error','message'=>$e->getMessage()], 400);
        }

    }

    public function loginWithPassword(Request $request)
    {
        
        // try {
            // if(Auth::guard('api')->user()){
            //     // $user = JWTAuth::parseToken()->authenticate()
            //     JWTAuth::invalidate(JWTAuth::getToken());
            // }
            // 
            $credentials = $request->only('email', 'password');
            // $jwt_token = null;
            // Retrieve the previous token from the request or any other source
                // $previousToken = $request->bearerToken();

                // Invalidate the previous token
                // JWTAuth::invalidate($previousToken);
            
    
            if (!$user = Auth::guard('api')->attempt($credentials)) {
            // if (! $jwt_token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Email or Password',
                ], Response::HTTP_UNAUTHORIZED);
            }
            $student = Auth::guard('api')->user();
            // JWTAuth::manager()->invalidate(new JWT($student->getJWTIdentifier()));
            // Invalidate the previous token
            
    
            return response()->json([
                'status' => true,
                'message' => "Login Successfully",
                'token' => JWTAuth::fromUser($student),
                'prepration'=>Auth::guard('api')->user()->prepration,
            ]);
        // } catch (TokenExpiredException $e) {
        //     // Token has expired, handle the exception here
        //     return response()->json(['error' => 'Token has expired'], 401);
        // }
    }

    public function refreshToken(Request $request){
        try {
            $token = JWTAuth::parseToken()->refresh();
    
            return response()->json([
                'status' => true,
                'message' => 'Token refreshed successfully',
                'token' => $token,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Token refresh failed',
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function loginWithOtp(Request $request)
    {

        $resetToken = $request->otp;

        $user = Student::where('id', $request->userId)->first();

        if ($user && Hash::check($resetToken, $user->reset_token)) {
            $jwt_token = Auth::guard('api')->login($user);

            return response()->json([
                'status' => true,
                'token' => $jwt_token,
                'prepration'=>Auth::guard('api')->user()->prepration,
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid OTP',
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function forgotPassword(Request $request){
        $validator = Validator::make($request->all(), 
            [ 
            'userId' => 'required',
            'newPassword' => 'required',  
            'c_password' => 'required|same:newPassword', 
        ]); 

        if ($validator->fails()) {  
            return response()->json(['error'=>$validator->errors()], 401); 
        }

        $user = Student::where('id', $request->userId)->first();

        if (!$user){
            return response()->json([
                'status' => false,
                'message' => 'Something Problem!',
            ], 401);
        }
        $user->password=Hash::make($request->c_password);

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Successfully Updated Password',
        ], 200);

    }

    public function unAuth(Request $request){
        return response()->json(['status' =>false, 'message' => 'Unauthorized'], 401);
    }

    public function logout()
    {
        try{
            Auth::guard('api')->logout();
            // JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'message' => 'Successfully logged out',
            ]);
        } catch (TokenExpiredException $e) {
            // Token has expired, handle the exception here
            return response()->json(['error' => 'Token has expired'], 401);
        }
    }

}

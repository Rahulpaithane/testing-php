<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail; 
use App\Mail\AdminPasswordReset;
use App\Models\User;
use App\Models\Tenant;
use Carbon\Carbon;
use Tenancy\Affects\Connections\Events\Resolving;

class AuthController extends Controller
{
    public function login(Request $request){
        // dd(Auth::guard('admin')->user()->id);
        if (Auth::guard('admin')->check()) {
            return redirect()->route($request->routePath.'.dashboard');
        }
        $route=route($request->routePath.'.login-auth');
        // dd($route);
        return view('backend.auth.login', ['route'=>$route]);
    }

    // Do Login
    public function loginAuth(Request $request){
        try{
            $request->validate([
                'email' => 'required',
                'password' => 'required|min:3|max:10',
            ]);

            $user = User::where('status', 1)->where('email', $request->email)->first();
            if ($user) {

            //     Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password, 'role_type'=> function ($query) {
            //         $query->where('role_type', '=', 'Admin');
            //    }

                if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password]) == true) {
                    return redirect()->route($request->routePath.'.dashboard');
                }else {
                    return redirect()->back()->with('fail', 'The email or password is incorrect, please try again!');
                }
            }else{
                return back()->with('fail', 'This Email is not registered.');
            }
        }catch(\Exception $e){
            //Authentication failed
            return redirect()->back()->with('fail','Authentication failed, kindly contact the Administrator!');
        }
    }


    // **********################### Institute Login Only $$$$$$$$$$$$$$

        public function login2(Request $request){
            // dd(Auth::guard('admin')->user()->id);
            if (Auth::guard('admin')->check()) {
                // $domain=request()->getHost();
                return redirect(tenant_route(request()->getHost(),'tenantAdmin.dashboard'));
                // return redirect()->tenant_route('admin.dashboard');
            }
            $route=route('tenantAdmin.login-auth');
            return view('backend.auth.login', ['route'=>$route]);
        }

        // Do Login
        public function loginAuth2(Request $request){
            try{
                $request->validate([
                    'email' => 'required',
                    'password' => 'required|min:3|max:10',
                ]);

                $user = User::where('status', 1)->where('email', $request->email)->first();
                if ($user) {

                //     Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password, 'role_type'=> function ($query) {
                //         $query->where('role_type', '=', 'Admin');
                //    }

                    if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password]) == true) {
                        return redirect()->route('tenantAdmin.dashboard');
                    }else {
                        return redirect()->back()->with('fail', 'The email or password is incorrect, please try again!');
                    }
                }else{
                    return back()->with('fail', 'This Email is not registered.');
                }
            }catch(\Exception $e){
                //Authentication failed
                return redirect()->back()->with('fail','Authentication failed, kindly contact the Administrator!');
            }
        }

    // ##################$$$$$$$$ End Institute Login &&&&&&&&&&&&&&&&&&&&&&

    // Start Forget Password
    public function  forgotPassword(){
        return view('backend.auth.forgotPassword');
    }

    public function passwordReset(Request $request){
        try{
            $data = $request->input();
            $validate_email = User::select('id', 'email')->where('email', $data['email'])->where('role_type', 'Admin')->first();
            if ($validate_email) {
                $resetToken = [
                    'reset_token' => encrypt(rand(100000, 1000000)),
                ];
                User::where('email', $data['email'])->update($resetToken);
                Mail::to($data['email'])->send(new AdminPasswordReset($resetToken));
                return redirect()->back()->with(['success' => "A Password Reset Link has been sent to your email successfully!"]);
            } else {
                return redirect()->back()->with(['fail' => "Opps.. Entered email is not registered!!"]);
            }

        }catch(Exception $e){
            return redirect()->back()->with(['status'=>'error', 'message'=>$e->getMessage()]);
        }
    }//End of Forget password

    //setting up new password page and submission
    public function authPasswordReset(Request $request){
        if ($request->method() == "GET") {
            $key = $request->key;
            $data = User::select('reset_token', 'email')->where('reset_token', $key)->first();
            if ($data == '') {
                return " 505 | Opps.. Token Expired!";
            }
            return view('backend.auth.resetPassword', ['data' => $data]);
        }

        if ($request->method() == "POST") {
            $request->validate([
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/[a-z]/',
                    'regex:/[A-Z]/',
                    'regex:/[0-9]/',
                    'regex:/[@$!%*#?&]/',
                ],
                'password_confirm' => 'required_with:password|same:password|min:8'
            ]);
            $data = User::where('email',$request->email)->update([
                'password' => Hash::make($request->password),
                'reset_token'=>'',
                'updated_at' => Carbon::now('Asia/Kolkata')
            ]);
            return redirect('admin')->with('success','Your Password has been changed successfully! you can login now.');
        }
    }

    public function institutePasswordReset(Request $request){
        if ($request->method() == "GET") {
            $key = decrypt($request->key);

            //     DB::setDefaultConnection('tenant'.$request->id);

            //     // $data = DB::connection('tenant'.$request->id)
            //    $data= DB::table('users')
            //     ->select('reset_token', 'email')
            //     ->where('reset_token', $key)->first();

            $tenant = Tenant::find($request->id);

            if ($tenant) {
                $userData = null;
                // Execute the callback within the context of the tenant
               $tenant->run(function () use($request, $key, &$userData) {
                    // Inside this closure, the database connection and other context-specific settings are automatically set for the tenant.
        
                    // You can now interact with the tenant's data, configuration, and resources.
        
                    // Fetch data from the tenant's 'users' table:
                    $userData = DB::table('users')
                    ->select('reset_token', 'email')
                    ->where('reset_token', $key)->first();
                    // dd($data);
                    if ($userData == '') {
                        return " 505 | Opps.. Token Expired!";
                    }

                    // $this->data=$userData;
                    
                    // Perform other operations specific to the tenant's 'users' table...
                });

                // dd($userData);

                return view('backend.auth.instituteResetPassword', ['data' => $userData, 'domainId'=>$request->id]);
            } else {
                // Handle the case when the tenant with the given ID is not found
            }
            

            // return view('backend.auth.instituteResetPassword', ['data' => $usersData, 'domainId'=>$request->id]);
            // $data = User::select('reset_token', 'email')->where('reset_token', $key)->first();
        }

        if ($request->method() == "POST") {
            $request->validate([
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/[a-z]/',
                    'regex:/[A-Z]/',
                    'regex:/[0-9]/',
                    'regex:/[@$!%*#?&]/',
                ],
                'password_confirm' => 'required_with:password|same:password|min:8'
            ]);

            $tenant = Tenant::find($request->id);

            if ($tenant) {
                $userData = null;
                // Execute the callback within the context of the tenant
               $tenant->run(function () use($request, &$userData) {

                    $userData = DB::table('users')
                    ->where('email',$request->email)
                    ->update([
                        'password' => Hash::make($request->password),
                        'reset_token'=>'',
                        'updated_at' => Carbon::now('Asia/Kolkata')
                    ]);
                });
                $redirectUrl = 'http://' . $request->id . '.' . request()->getHost().':8000';
                return redirect()->away($redirectUrl);

                // return view('backend.auth.instituteResetPassword', ['data' => $userData, 'domainId'=>$request->id]);
            } else {
                // Handle the case when the tenant with the given ID is not found
            }



            // return redirect($request->id.'.'.request()->getHost());
            // return redirect('admin')->with('success','Your Password has been changed successfully! you can login now.');
        }
    }



    // public function login(Request $request){

    //     if($request->method() == 'GET'){
    //         // return view('backend.home.index');
    //         return view('backend.auth.login');
    //     } else {

    //         if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password]) == true) {

    //             return redirect()->intended(route('admin.homepage'));
    //         } else {

    //             return redirect()->back()->with('message', 'The email or password is incorrect, please try again!');
    //         }

    //     }
    // }

}

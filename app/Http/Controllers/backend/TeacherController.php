<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendWelcomeEmailJob;

class TeacherController extends Controller
{
    //GOTO AND ADD NEW TEACHERS
    public function manageTeachers(Request $request){
        try{
            if($request->method() == 'GET'){
                return view('backend.teacher.teachersList');
            }

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                // 'email' => 'required|email|unique:users,email',
                'mobile' => 'required|digits:10',
                // 'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'gender' => 'required|string|max:255',
                'aadhar_no' => 'required|digits:12',
                'education' => 'required|string|max:255',
                'address' => 'required|string|max:400',
            ]);

            if (isset($request->profile_image) != null) {
                $filename = uniqid() . $request->profile_image->getClientOriginalName();
                $path = 'images/'.$filename;
                $request->profile_image->move(public_path('images'), $filename);
            }else{
                    $path = $request->existing_teacher_image;
            }
                DB::beginTransaction();

                if($request->teacherId =='' || !$teachers=User::where('id', '=', $request->teacherId)->first() ) {
                    $teachers = new User();
                }

                $teachers->name=$request->name;
                $teachers->email=$request->email;
                $teachers->mobile=$request->mobile;
                $teachers->aadhar_no=$request->aadhar_no;
                $teachers->gender=$request->gender;
                $teachers->education=$request->education;
                $teachers->address=$request->address;
                $teachers->profile_image=$path;
                $teachers->role_type='Teacher';
                $request->teacherId =='' ? $teachers->password=Hash::make($request->mobile) : '';
                $teachers->save();
                
      
            DB::commit();

            $details['name'] = $request->name;
            $details['email'] = $request->email;
            $details['password'] = $request->mobile;
            dispatch(new SendWelcomeEmailJob($details));

            $callback=['load_data'];
            $closedPopup='myModal';
            return response()->json(["statuscode" => '201', "message" => 'Teacher has been Added Successfully!!', "actionType"=>"003", "callback"=>$callback, 'closedPopup'=>$closedPopup]);
        }catch(\ValidationException $e){
            DB::rollback();
            Log::error($e->getMessage());
            return response()->json(['errors' =>$e->getMessage()], 422);
        }
     }

     //TO SHOW THE REGISTERED TEACHERS LIST
     public function teachersList(Request $request){
        try{
            $teachers = User::where('role_type', 'Teacher');
            return \DataTables::eloquent($teachers)
            ->addIndexColumn()
            ->addColumn('status', function ($data) use($request) {
                if ($data->status == '1') {
                    $status = '<a href="javascript:void(0);" class="toggleStatus alert alert-success" style="text-decoration:none;" data="' . route($request->routePath.'.updateTeacherStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >Active</a>';
                } else {
                    $status = '<a href="javascript:void(0);" class="toggleStatus alert alert-danger" style="text-decoration:none;" data="' . route($request->routePath.'.updateTeacherStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >In-Active</a>';
                }
                return $status;
            })
            ->addColumn('action', function($data) use($request) {
                $button='<center>';
                $button .= '<a href="javascript:void(0);" onClick="editTeacherModal(' . htmlentities(json_encode($data)) . ')" class="edit_batch" data="' . route($request->routePath.'.manageTeachers') . '" id="' . $data->id . '"><i class="fas fa-edit bg-light" style="font-size:20px; color:green;"></i></a>';
                $button.='&#160;&#160;&#160;&#160;';
                $button.='  <a href="javascript:void(0);" class="delete2023" url="'.route($request->routePath.'.deleteTeacher').'" id="'.$data->id.'"  ><i class="fas fa-trash-alt bg-light" style="font-size:20px;color:red;"></i></a>';
                $button.='</center>';
                return $button;
            })
            ->addColumn('profile_image', function($data){
                $url = asset($data->profile_image);
                $img='<a href="'.$url.'" target="_blank" class=""><img src="'.$url.'" width="40px" height="40px" class="img-rounded" align="center" alt="file" /></a>';
                return $img;
            })
            ->rawColumns(array("status","action", "profile_image"))
            ->make(true);
        }catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred'], 500);
        }
     }

     public function updateTeacherStatus(Request $request){
        $record = User::find($request->id);
        $record->status = $request->status;
        $record->save();
        return response()->json(["statuscode" => '001', "message" => 'Status Updated Successfully!!' ]);
     }

     public function deleteTeacher(Request $request){
        try{
            DB::beginTransaction();
            $teacher = User::find($request->id);
            $teacher->delete();

            DB::commit();
            return response()->json(["statuscode" => '001', "message" => 'Record Deleted Successfully!!' ]);
        }catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return response()->json(["statuscode" => '000', 'errors' => $e->getMessage()], 500);
        }
     }
}

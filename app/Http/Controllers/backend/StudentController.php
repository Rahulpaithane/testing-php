<?php

namespace App\Http\Controllers\backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use DataTables;

class StudentController extends Controller
{
    public function manageStudent(Request $request){
        if($request->method() == 'GET'){
            
            return view('backend.student.studentList', []);
        }

        $batchData = Student::orderBy('id', 'DESC');

        return DataTables::eloquent($batchData)
        ->addIndexColumn()
        ->addColumn('status', function ($data) use($request) {
            if ($data->status == '1') {
                $status = '<a href="javascript:void(0);" class="toggleStatus alert alert-success" style="text-decoration:none;" data="' . route($request->routePath.'.updateStudentStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >Active</a>';
            } else {
                $status = '<a href="javascript:void(0);" class="toggleStatus alert alert-danger" style="text-decoration:none;" data="' . route($request->routePath.'.updateStudentStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >In-Active</a>';
            }
            return $status; 
        })
        ->addColumn('action', function($data) use($request) {
            $button='<center>';
            // $button .= '<a href="javascript:void(0);" onClick="editBatch(' . htmlentities(json_encode($data)) . ')" class="edit_batch" data="' . route($request->routePath.'.batchManage') . '" id="' . $data->id . '"><i class="fas fa-edit bg-light" style="font-size:20px; color:green;"></i></a>';
            // $button.='&#160;&#160;&#160;&#160;';
            $button.='  <a href="javascript:void(0);" class="delete2023" url="'.route($request->routePath.'.deleteStudent').'" id="'.$data->id.'"  ><i class="fas fa-trash-alt bg-light" style="font-size:20px;color:red;"></i></a>';
            $button.='</center>';
            return $button;
        })

        ->addColumn('profile_image', function($data){
            $url = asset($data->profile_image);
            $img='<a href="'.$url.'" target="_blank" class=""><img src="'.$url.'" width="40px" height="40px" class="img-rounded" align="center" alt="file" /></a>';
            return $img;
        })
        ->rawColumns(array('profile_image', 'status', 'action'))
        ->make(true);
    }

    public function updateStudentStatus(Request $request){
        $record = Student::find($request->id);
        $record->status = $request->status;
        $record->save();
        return response()->json(["statuscode" => '001', "message" => 'Status Updated Successfully!!' ]);
     }

     public function deleteStudent(Request $request){
        try{
            DB::beginTransaction();
            $batch = Student::find($request->id)->delete();

            DB::commit();
            return response()->json(["statuscode" => '001', "message" => 'Record Deleted Successfully!!' ]);
        }catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return response()->json(["statuscode" => '000', 'errors' => $e->getMessage()], 500);
        }
    }
}

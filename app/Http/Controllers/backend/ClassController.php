<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassModel;
use DataTables;

class ClassController extends Controller
{
    public function manageClasses(Request $request){
        try{
            if($request->method() == 'GET'){
                return view('backend.class.classList');
            }

            $validatedData = $request->validate([
                'class_name' => 'required',
                'prepration' => 'required',
                // 'class_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $message="Class Updated Successfully!!";

            if (isset($request->class_image) != null) {
                $filename = uniqid() . $request->class_image->getClientOriginalName();
                $path = 'images/'.$filename;
                $request->class_image->move(public_path('images'), $filename);
            }else{
                    $path = $request->existing_class_image;
            }
            
            if($request->classId =='' || !$classAdd=ClassModel::where('id', '=', $request->classId)->first() ) {
                    $classAdd = new ClassModel();
                    $message="Class Added Successfully!!";
            }
            $classAdd->name=$request->class_name;
            $classAdd->prepration=$request->prepration;
            $classAdd->image=$path;
            $classAdd->added_by=Auth::guard('admin')->user()->id;
            $classAdd->save();

            $callback=['load_data'];
            $closedPopup='myModal';
            return response()->json(["statuscode" => '201', "message" => $message, "actionType"=>"003", "callback"=>$callback, 'closedPopup'=>$closedPopup]);
        }catch(\ValidationException $e){
            Log::error($e->getMessage());
            return response()->json(['errors' =>$e->getMessage()], 422);
        }
     }

    public function classesList(Request $request){
        try{

            $classes = ClassModel::query();
            return DataTables::eloquent($classes)
            ->addIndexColumn()
            ->addColumn('status', function ($data) use($request) {
                if ($data->status == '1') {
                    $status = '<a href="javascript:void(0);" class="toggleStatus alert alert-success" style="text-decoration:none;" data="' . route($request->routePath.'.updateClassStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >Active</a>';
                } else {
                    $status = '<a href="javascript:void(0);" class="toggleStatus alert alert-danger" style="text-decoration:none;" data="' . route($request->routePath.'.updateClassStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >In-Active</a>';
                }
                return $status;
            })
            ->addColumn('action', function($data) use($request) {
                $button='<center>';
                $button .= '<a href="javascript:void(0);" onClick="editClass(' . htmlentities(json_encode($data)) . ')" class="edit_employee" data="' . route($request->routePath.'.manageClasses') . '" id="' . $data->id . '"><i class="fas fa-edit bg-light" style="font-size:20px; color:green;"></i></a>';
                $button.='&#160;&#160;&#160;&#160;';
                $button.='  <a href="javascript:void(0);" class="delete2023" url="'.route($request->routePath.'.classesDelete').'" id="'.$data->id.'"  ><i class="fas fa-trash-alt bg-light" style="font-size:20px;color:red;"></i></a>';
                $button.='</center>';
                return $button;
            })
            ->addColumn('image', function($data){
                $url = asset($data->image);
                $img='<a href="'.$url.'" target="_blank" class=""><img src="'.$url.'" width="40px" height="40px" class="img-rounded" align="center" alt="file" /></a>';
                return $img;
            })
            ->rawColumns(array("status", "image", "action"))
            ->make(true);
        }catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    public function updateClassStatus(Request $request){
        $record = ClassModel::find($request->id);
        $record->status = $request->status;
        $record->save();
        return response()->json(["statuscode" => '001', "message" => 'Status Updated Successfully!!' ]);
    }

    public function classesDelete(Request $request){
        $record = ClassModel::find($request->id);
        $record->delete();
        $record->save();
        return response()->json(["statuscode" => '001', "message" => 'Record Deleted Successfully!!' ]);
    }
}

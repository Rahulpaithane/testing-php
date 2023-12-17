<?php
 
namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MobileSlider;
use Auth;
use DataTables;
use App\Models\ClassModel;

class BannerController extends Controller
{
    public function bannerManage(Request $request){
        if($request->method() == 'GET'){
            $classModel=ClassModel::where('status','1')->get();
            return view('backend.slider.manageSlider', ['classModel'=>$classModel]);
        }

        $validatedData = $request->validate([
            'prepration' => 'required',
            // 'class_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $message="Banner Updated Successfully!!";

        if (isset($request->banner) != null) {
            $filename = uniqid() . $request->banner->getClientOriginalName();
            $path = 'images/'.$filename;
            $request->banner->move(public_path('images'), $filename);
            }else{
                $path = $request->existing_banner;
            }
        
        if($request->bannerId =='' || !$roomAdd=MobileSlider::where('id', '=', $request->bannerId)->first() ) {
                $roomAdd = new MobileSlider();
                $message="Banner Added Successfully!!";
        }
        $roomAdd->prepration=$request->prepration;
        $roomAdd->image=$path;
        $roomAdd->isClickable=$request->isClickable;
        $roomAdd->class_id=$request->class;
        $roomAdd->batch_id=$request->batch;
        $roomAdd->save();

        $callback=['load_data'];
        $closedPopup='myModal';
        return response()->json(["statuscode" => '201', "message" => $message, "actionType"=>"003", "callback"=>$callback, 'closedPopup'=>$closedPopup]);
    }

    public function bannerList(Request $request){
        try{

            $banner = MobileSlider::query();
            return DataTables::eloquent($banner)
            ->addIndexColumn()
            ->addColumn('status', function ($data) use($request) {
                if ($data->status == '1') {
                    $status = '<a href="javascript:void(0);" class="toggleStatus alert alert-success" style="text-decoration:none;" data="' . route($request->routePath.'.bannerStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >Active</a>';
                } else {
                    $status = '<a href="javascript:void(0);" class="toggleStatus alert alert-danger" style="text-decoration:none;" data="' . route($request->routePath.'.bannerStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >In-Active</a>';
                }
                return $status;
            })
            ->addColumn('action', function($data) use($request) {
                $button='<center>';
                $button .= '<a href="javascript:void(0);" onClick="editBanner(' . htmlentities(json_encode($data)) . ')" class="edit_employee" data="' . route($request->routePath.'.bannerManage') . '" id="' . $data->id . '"><i class="fas fa-edit bg-light" style="font-size:20px; color:green;"></i></a>';
                $button.='&#160;&#160;&#160;&#160;';
                $button.='  <a href="javascript:void(0);" class="delete2023" url="'.route($request->routePath.'.bannerDelete').'" id="'.$data->id.'"  ><i class="fas fa-trash-alt bg-light" style="font-size:20px;color:red;"></i></a>';
                $button.='</center>';
                return $button;
            })
          
            ->addColumn('image', function($data){
                $url = $data->image;
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

    public function bannerStatus(Request $request){
        $record = MobileSlider::find($request->id);
        $record->status = $request->status;
        $record->save();
        return response()->json(["statuscode" => '001', "message" => 'Status Updated Successfully!!' ]);
    }

    public function bannerDelete(Request $request){
        $record = MobileSlider::find($request->id);
        $record->delete();
        $record->save();
        return response()->json(["statuscode" => '001', "message" => 'Record Deleted Successfully!!' ]);
    }
}

<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassModel;
use App\Models\StudentNotification;
use DataTables;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function notificationManage(Request $request){
        if($request->method() == 'GET'){

            $classCategory = ClassModel::where('status', 1)->get();
            return view('backend.notification.notificationList', ['classCategory'=>$classCategory]);
        }

        if($request->notificationId =='') {
            $notice = new StudentNotification();
        } else {
            $notice=StudentNotification::where('id', '=', $request->notificationId)->first();
        }

        $notice->targetStudent = $request->targetStudent;
        $notice->class_id = $request->class_id;
        $notice->title =$request->title;
        $notice->message = $request->message;
        $notice->save();


        $callback=['load_data'];
        $closedPopup='myModal';
        return response()->json(["statuscode" => '201', "message" => 'Notification Created Successfully!', "actionType"=>"003", "callback"=>$callback, "closedPopup"=>$closedPopup]);
    }

    public function notificationList(Request $request){
        $noticeData = StudentNotification::with('class');

        return DataTables::eloquent($noticeData)
        ->addIndexColumn()


        ->addColumn('status', function ($data) use($request) {
            $status='<center>';
            if ($data->status == '1') {
                $status= '<a href="javascript:void(0);" class="toggleStatus alert alert-success" style="text-decoration:none;" data="' . route($request->routePath.'.updateNotificationStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >Active</a>';
            } else {
                $status= '<a href="javascript:void(0);" class="toggleStatus alert alert-danger" style="text-decoration:none;" data="' . route($request->routePath.'.updateNotificationStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >In-Active</a>';
            }
            $status.='</center>';
            return $status; 
        })
        ->addColumn('action', function($data) use($request) {
            $button='<center>';
            $button .= '<a href="javascript:void(0);" onClick="editNotification(' . htmlentities(json_encode($data)) . ')" class="edit_batch" data="' . route($request->routePath.'.notificationManage') . '" id="' . $data->id . '"><i class="fas fa-edit bg-light" style="font-size:20px; color:green;"></i></a>';
            $button.='&#160;&#160;&#160;&#160;';
            $button.='  <a href="javascript:void(0);" class="delete2023" url="'.route($request->routePath.'.deleteNotification').'" id="'.$data->id.'"  ><i class="fas fa-trash-alt bg-light" style="font-size:20px;color:red;"></i></a>';
            $button.='</center>';
            return $button;
        })
 
        ->rawColumns(array('status', 'action'))
        ->make(true);
    }

    public function updateNotificationStatus(Request $request){
        $record = StudentNotification::find($request->id);
        $record->status = $request->status;
        $record->save();
        return response()->json(["statuscode" => '001', "message" => 'Status Updated Successfully!!' ]);
    }

    public function deleteNotification(Request $request){
        try{
            $n = StudentNotification::find($request->id);
            $n->delete();

            DB::commit();
            return response()->json(["statuscode" => '001', "message" => 'Record Deleted Successfully!!' ]);
        }catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return response()->json(["statuscode" => '000', 'errors' => $e->getMessage()], 500);
        }
    }
    
}
 